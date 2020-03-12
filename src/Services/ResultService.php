<?php


namespace QuizApp\Services;


use Psr\Http\Message\RequestInterface;
use QuizApp\Repositories\QuestionInstanceRepository;
use QuizApp\Repositories\ResultRepository;
use QuizApp\Repositories\TextInstanceRepository;
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

        $resultRepository = $this->repositoryManager->getRepository(ResultRepository::class);

        return $resultRepository->findBy([], [], 10, $from);
    }

    public function countQuestion(string $quizInstanceId)
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(QuestionInstanceRepository::class);

        $count = $textInstanceRepository->countInstancesWithQuestionInstance($quizInstanceId);

        return $count['COUNT(quizInstanceId)'];
    }

    public function getQuestionsInstance($quizInstanceId)
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(QuestionInstanceRepository::class);

        return $textInstanceRepository->findBy(['quizInstanceId' => $quizInstanceId], [], 0, 0);
    }

    public function getTextInstance($id)
    {
        $textInstanceRepository = $this->repositoryManager->getRepository(TextInstanceRepository::class);

        return $textInstanceRepository->findBy(['questionInstanceId' => $id], [], 0, 0);
    }
}