<?php


namespace QuizApp\Services;


use HighlightLib\CodeHighlight;
use Psr\Http\Message\RequestInterface;
use QuizApp\DTOs\QuestionDTO;
use QuizApp\Entities\QuestionInstance;
use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\TextInstance;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;

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

    public function getAllUserTests(RequestInterface $request)
    {
        $page = $request->getRequestParameters();
        $from = ($page['offset'] - 1) * 10;

        $resultRepository = $this->repositoryManager->getRepository(QuizInstance::class);

        return $resultRepository->findBy([], [], 10, $from);
    }

    public function countQuestion(string $quizInstanceId)
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(QuestionInstance::class);

        $count = $textInstanceRepository->countInstancesWithQuestionInstance($quizInstanceId);

        return $count['COUNT(quizInstanceId)'];
    }

    public function getQuestionsInstance($quizInstanceId)
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(QuestionInstance::class);

        return $textInstanceRepository->findBy(['quizInstanceId' => $quizInstanceId], [], 0, 0);
    }

    public function getTextInstance($id)
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(TextInstance::class);

        return $textInstanceRepository->findOneBy(['questionInstanceId' => $id]);
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
}