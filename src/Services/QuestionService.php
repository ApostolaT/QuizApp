<?php


namespace QuizApp\Services;


use Framework\Controller\AbstractController;
use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\QuestionTemplate;
use QuizApp\Repositories\QuestionRepository;
use ReallyOrm\Repository\RepositoryManagerInterface;

class QuestionService extends AbstractService
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

        $repository = $this->repositoryManager->getRepository(QuestionRepository::class);

        $entities = $repository->findBy([], [], 10, 0);

        return $entities;
    }

    public function createEntity($request)
    {
        $repository = $this->repositoryManager->getRepository(QuestionRepository::class);
        $name = $request->getParameter('email');
        $type = $request->getParameter('role');
        $password = "";

        $entity = new QuestionTemplate();
        $entity->setName($name);
        $entity->setPassword($password);
        $entity->setRole($type);

        return $repository->insertOnDuplicateKeyUpdate($entity);
    }

    public function delete($request) {
        $repository = $this->repositoryManager->getRepository(QuestionRepository::class);
        $id = $request->getRequestParameters()['id'];

        $entity = $repository->find((int)$id);

        return $repository->delete($entity);
    }

    public function getUpdatePageParams(RequestInterface $request)
    {
        $id = $request->getRequestParameters()['id'];
        $repository = $this->repositoryManager->getrepository(QuestionRepository::class);
        $entity = $repository->find((int)$id);

        return $entity;
    }

    public function updateEntity($request, $session)
    {
        //TODO make update in quizzes like here
        $repository = $this->repositoryManager->getRepository(QuestionRepository::class);
        $id = $request->getRequestParameters()['id'];
        $entity = $repository->find((int)$id);
        $name = $request->getParameter('email');
        $role = $request->getParameter('role');

        $entity->setName($name);
        $entity->setRole($role);

        return $repository->insertOnDuplicateKeyUpdate($entity);
    }
}