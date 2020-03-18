<?php

namespace QuizApp\Services;

use HighlightLib\CodeHighlight;
use Psr\Http\Message\RequestInterface;
use QuizApp\DTOs\QuestionDTO;
use QuizApp\DTOs\QuizInstanceDTO;
use QuizApp\Entities\QuestionInstance;
use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\TextInstance;
use QuizApp\Entities\User;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;

/**
 * Class ResultService
 * serves ResultController for getting Data from data base
 * and populating the corresponding Data Transfer Objects or
 * with saving data to DataBase
 * @package QuizApp\Services
 */
class ResultService extends AbstractService
{
    private $repositoryManager;

    private $codeHighLighter;

    public function setCodeHighLighter(CodeHighlight $codeHighlight)
    {
        $this->codeHighLighter = $codeHighlight;
    }

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function getAllUserTests(array $page): array
    {
        $from = ($page['offset'] - 1) * 10;

        return $this->getQuizInstanceDTOsWithOffset($from);
    }

    public function scoreResult(string $score, string $quizInstanceId): bool
    {
        $quizInstanceRepository = $this->repositoryManager->getRepository(QuizInstance::class);

        try {
            $quizInstanceEntity = $quizInstanceRepository->find((int)$quizInstanceId);
            $quizInstanceEntity->setScore($score);
            return $quizInstanceEntity->save($quizInstanceEntity);
        } catch (NoSuchRowException $e) {
            return false;
        }
    }

    public function getQuestionsAnswersForQuizWithId(string $quizInstanceId): ?array
    {
        $questions = $this->getQuestionsForQuizId($quizInstanceId);

        if (!$questions) {
            return null;
        }

        $questionDTOs = [];
        foreach ($questions as $question) {
            $answer = $this->getAnswerForQuestionId($question->getId());

            if (!$answer) {
                return null;
            }

            $answerText =
                ($question->getType() === 'code')
                ? $this->codeHighLighter->highlight($answer->getText())
                : $answer->getText();

            $questionDTOs[] = new QuestionDTO($question->getText(), $answerText);
        }

        return $questionDTOs;
    }

    private function getQuestionsForQuizId(string $quizInstanceId): ?array
    {
        $questionInstanceRepository = $this->repositoryManager->getRepository(QuestionInstance::class);

        try {
            return $questionInstanceRepository->findBy(['quizInstanceId' => $quizInstanceId], [], 0, 0);
        } catch (NoSuchRowException $e) {
            return null;
        }
    }

    private function getAnswerForQuestionId(string $getId): ?TextInstance
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(TextInstance::class);
        try {
            return $textInstanceRepository->findOneBy(['questionInstanceId' => $getId]);
        } catch (NoSuchRowException $e) {
            return null;
        }
    }

    //TODO you can think about a small "caching" mechanism here if you want.
    //you might perform this query too many times with the same id
    private function getQuizInstanceDTOsWithOffset(string $offset): array
    {
        $resultRepository = $this->repositoryManager->getRepository(QuizInstance::class);
        $userRepository = $this->repositoryManager->getRepository(User::class);

        $quizInstances = $resultRepository->findBy([], [], 10, $offset);
        $quizInstanceDTOs = [];
        foreach ($quizInstances as $quizInstance) {
            $userEntity = $userRepository->find($quizInstance->getUserId());
            $score = $quizInstance->getScore();
            $quizInstanceDTO = new QuizInstanceDTO(
                $quizInstance->getId(),
                $quizInstance->getName(),
                $quizInstance->getUserId(),
                $quizInstance->getQuizTemplateId(),
                $userEntity->getName(),
                $score ?? ""
            );

            $quizInstanceDTOs[] = $quizInstanceDTO;
        }

        return $quizInstanceDTOs;
    }
}
