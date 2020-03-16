<?php


namespace QuizApp\Services;


use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\QuestionInstance;
use QuizApp\Entities\QuizInstance;
use QuizApp\Entities\TextInstance;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;

class ResultService extends AbstractService
{
    private $repositoryManager;

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

        return $textInstanceRepository->findBy(['questionInstanceId' => $id], [], 0, 0);
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
}