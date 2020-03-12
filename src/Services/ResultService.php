<?php


namespace QuizApp\Services;


use Psr\Http\Message\RequestInterface;
use QuizApp\Repositories\ResultRepository;
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
}