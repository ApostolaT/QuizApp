<?php


namespace QuizApp\Services;


use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\QuizTemplate;
use QuizApp\Repositories\QuizRepository;
use QuizApp\Repositories\QuizTypeRepository;
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

        $entities = $repository->findBy([], [], 10, 0);

        return $entities;
    }

    public function getType()
    {
        $repository = $this->repositoryManager->getRepository(QuizTypeRepository::class);

        // TODO create a findAll in abstractRepository
        return $repository->findBy([], [], 0, 0);
    }

    public function createEntity($request, $session)
    {
        $repository = $this->repositoryManager->getRepository(QuizRepository::class);
        $name = $request->getParameter('name');
        $type = $request->getParameter('type');

        $createdBy = $session->get('id');
        $entity = new QuizTemplate();
        $entity->setName($name);
        $entity->setCreatedBy($createdBy);
        $entity->setType($type);

        return $repository->insertOnDuplicateKeyUpdate($entity);
    }

    public function delete($request) {
        $repository = $this->repositoryManager->getRepository(QuizRepository::class);
        $id = $request->getRequestParameters()['id'];

        $entity = $repository->find((int)$id);

        return $repository->delete($entity);
    }

    public function getUpdatePageParams(RequestInterface $request)
    {
        $id = $request->getRequestParameters()['id'];
        $repository = $this->repositoryManager->getrepository(QuizRepository::class);
        $entity = $repository->find((int)$id);

        return $entity;
    }

    public function updateEntity($request, $session)
    {
        $repository = $this->repositoryManager->getRepository(QuizRepository::class);
        $name = $request->getParameter('name');
        $type = $request->getParameter('type');
        $id = $request->getRequestParameters()['id'];

        $createdBy = $session->get('id');
        $entity = new QuizTemplate();
        $entity->setId($id);
        $entity->setName($name);
        $entity->setCreatedBy($createdBy);
        $entity->setType($type);

        return $repository->insertOnDuplicateKeyUpdate($entity);
    }
}
