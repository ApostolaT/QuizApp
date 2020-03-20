<?php

namespace QuizApp\Services;

use HighlightLib\CodeHighlight;
use QuizApp\DTOs\QuestionDTO;
use QuizApp\DTOs\QuizInstanceDTO;
use QuizApp\Entities\QuestionInstance;
use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\TextInstance;
use QuizApp\Entities\User;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

/**
 * Class ResultService
 * serves ResultController for getting Data from data base
 * and populating the corresponding Data Transfer Objects or
 * with saving data to DataBase
 * @package QuizApp\Services
 */
class ResultService extends AbstractService
{
    /**
     * Constant for pagination.
     */
    private const RESULTS_PER_PAGE = 10;
    /**
     * @var RepositoryManager
     */
    private $repositoryManager;
    /**
     * @var CodeHighlight
     */
    private $codeHighLighter;
    /**
     * Sets the codeHighlight
     * @param CodeHighlight $codeHighlight
     */
    public function setCodeHighLighter(CodeHighlight $codeHighlight)
    {
        $this->codeHighLighter = $codeHighlight;
    }
    /**
     * Sets the repositoryManager.
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }
    /**
     * Gets all the userTakenQuizzes in form of an array of QuizInstanceDTO
     * @param int $page
     * @return array|null
     * @throws \Exception
     */
    public function getAllUserTests(int $page): ?array
    {
        $offset = ($page - 1) * $this::RESULTS_PER_PAGE;

        try {
            $entities = $this->getQuizInstanceDTOsWithOffset($offset);
        } catch (NoSuchRowException $e) {
            $entities = null;
        }

        return $entities;
    }
    /**
     * Counts how many user entities the Repository has.
     * @return mixed
     * @throws \Exception
     */
    public function countRows()
    {
        $quizInstanceRepository = $this->repositoryManager->getRepository(QuizInstance::class);

        return $quizInstanceRepository->countRows();
    }
    /**
     * This function updates the quizInstance whose id = $quizInstanceId
     * with score = $score
     * @param string $score
     * @param string $quizInstanceId
     * @return bool
     * @throws \Exception
     */
    public function scoreResult(string $score, string $quizInstanceId): bool
    {
        $quizInstanceRepository = $this->repositoryManager->getRepository(QuizInstance::class);

        try {
            $quizInstanceEntity = $quizInstanceRepository->find((int)$quizInstanceId);
            $quizInstanceEntity->setScore($score);
            return $quizInstanceEntity->save();
        } catch (NoSuchRowException $e) {
            return false;
        }
    }
    //TODO redo the phtml to know when the userId is null.
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

    /**
     * Gets all the questions for the quizInstance with the quizInstanceId
     * @param string $quizInstanceId
     * @return array|null
     * @throws \Exception
     */
    private function getQuestionsForQuizId(string $quizInstanceId): ?array
    {
        $questionInstanceRepository = $this->repositoryManager->getRepository(QuestionInstance::class);
        //TODO refactor this
        try {
            return $questionInstanceRepository->findBy(['quizInstanceId' => $quizInstanceId], [], 0, 0);
        } catch (NoSuchRowException $e) {
            return null;
        }
    }

    /**
     * Gets all the answer for the question with the questionId
     * @param string $questionId
     * @return TextInstance|null
     * @throws \Exception
     */
    private function getAnswerForQuestionId(string $questionId): ?TextInstance
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(TextInstance::class);
        //TODO refactor this
        try {
            return $textInstanceRepository->findOneBy(['questionInstanceId' => $questionId]);
        } catch (NoSuchRowException $e) {
            return null;
        }
    }

    //TODO you can think about a small "caching" mechanism here if you want.
    //you might perform this query too many times with the same id
    /**
     * This function creates the DTOs for quizzes taken by users
     * by merging data from QuizInstance entity and User entity.
     * An array of DTOs is returned if quizzes exist, else
     * an empty array is returned.
     * @param string $offset
     * @return array
     * @throws \Exception
     */
    private function getQuizInstanceDTOsWithOffset(string $offset): array
    {
        $resultRepository = $this->repositoryManager->getRepository(QuizInstance::class);
        $userRepository = $this->repositoryManager->getRepository(User::class);

        $quizInstances = $resultRepository->findBy([], [], $this::RESULTS_PER_PAGE, $offset);
        $quizInstanceDTOs = [];
        foreach ($quizInstances as $quizInstance) {
            $userEntity = ($quizInstance->getUserId())
                ? $userRepository->find($quizInstance->getUserId())
                : null;
            $score = $quizInstance->getScore();
            $quizInstanceDTO = new QuizInstanceDTO(
                $quizInstance->getId(),
                $quizInstance->getName(),
                ($quizInstance->getUserId()) ?? "",
                $quizInstance->getQuizTemplateId(),
                ($userEntity) ? $userEntity->getName() : "",
                $score ?? ""
            );
            $quizInstanceDTOs[] = $quizInstanceDTO;
        }

        return $quizInstanceDTOs;
    }
}
