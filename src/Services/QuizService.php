<?php


namespace QuizApp\Services;


use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\QuizType;
use QuizApp\Repositories\QuizRepository;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuizService extends AbstractService
{
    private $repositoryManager;

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function getAll(RequestInterface $request)
    {
        $page = $request->getRequestParameters();
        $from = ($page['page'] - 1) * 10;

        $repository = $this->repositoryManager->getRepository(QuizRepository::class);

        $entities = $repository->findBy([], [], $from, 10);

        return $entities;
    }

    public function getType(RequestInterface $request)
    {
        $repository = $this->repositoryManager->getRepository(QuizType::class);
    }
}