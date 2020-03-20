<?php

namespace QuizApp\Services;

use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\User;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class UserService extends AbstractService
{
    /**
     * @var RepositoryManager
     */
    private $repositoryManager;

    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }

    public function getAll(int $page): ?array
    {
        $offset = ($page - 1) * 10;

        $repository = $this->repositoryManager->getRepository(User::class);

        try {
            $entities = $repository->findBy([], [], 10, $offset);
        } catch (NoSuchRowException $e) {
            $entities = null;
        }

        return $entities;
    }

    public function countRows()
    {
        $userRepository = $this->repositoryManager->getRepository(User::class);

        return $userRepository->countRows();
    }

    public function createEntity($request)
    {
        $repository = $this->repositoryManager->getRepository(User::class);
        $name = $request->getParameter('email');
        $type = $request->getParameter('role');
        $password = "";

        $entity = new User();
        $entity->setName($name);
        $entity->setPassword($password);
        $entity->setRole($type);

        return $repository->insertOnDuplicateKeyUpdate($entity);
    }

    public function delete($request) {
        $repository = $this->repositoryManager->getRepository(User::class);
        $id = $request->getRequestParameters()['id'];

        $entity = $repository->find((int)$id);

        return $repository->delete($entity);
    }

    public function getUpdatePageParams(RequestInterface $request)
    {
        $id = $request->getRequestParameters()['id'];
        $repository = $this->repositoryManager->getrepository(User::class);
        $entity = $repository->find((int)$id);

        return $entity;
    }

    public function updateEntity($request)
    {
        //TODO make update in quizzes like here
        $repository = $this->repositoryManager->getRepository(User::class);
        $id = $request->getRequestParameters()['id'];
        $entity = $repository->find((int)$id);
        $name = $request->getParameter('email');
        $role = $request->getParameter('role');

        $entity->setName($name);
        $entity->setRole($role);

        return $repository->insertOnDuplicateKeyUpdate($entity);
    }
}