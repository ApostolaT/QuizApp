<?php

namespace QuizApp\Services;

use Psr\Http\Message\RequestInterface;
use QuizApp\Entities\User;
use ReallyOrm\Exceptions\NoSuchRepositoryException;
use ReallyOrm\Exceptions\NoSuchRowException;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class UserService extends AbstractService
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
     * Sets the repositoryManager.
     * @param RepositoryManagerInterface $repositoryManager
     */
    public function setRepositoryManager(RepositoryManagerInterface $repositoryManager)
    {
        $this->repositoryManager = $repositoryManager;
    }
    /**
     * Extracts from repository $this::RESULTS_PER_PAGE users entities
     * with the offset equal to page - 1 * $this::RESULTS_PER_PAGE.
     * If no results found, it will return null.
     * @param int $page
     * @return array|null
     */
    public function getAll(int $page): ?array
    {
        $offset = ($page - 1) * $this::RESULTS_PER_PAGE;

        try {
            $repository = $this->repositoryManager->getRepository(User::class);
        } catch (NoSuchRepositoryException $e) {
            return null;
        }

        try {
            $entities = $repository->findBy([], [], $this::RESULTS_PER_PAGE, $offset);
        } catch (NoSuchRowException $e) {
            $entities = null;
        }

        return $entities;
    }
    /**
     * Counts how many user entities the Repository has.
     * @return mixed
     */
    public function countRows()
    {
        try {
            $userRepository = $this->repositoryManager->getRepository(User::class);
        } catch (NoSuchRepositoryException $e) {
            return ['rows' => 0];
        }

        return $userRepository->countRows();
    }

    /**
     * This function counts all the user entities from the user repository
     * that have the role like $role
     * @param string $role
     * @return array
     */
    public function countRowsLike(string $role): array
    {
        try {
            $userRepository = $this->repositoryManager->getRepository(User::class);
        } catch (NoSuchRepositoryException $e) {
            return ['rows' => 0];
        }

        return $userRepository->countRowsBy(['role' => $role]);
    }
    /**
     * This function returns true if a user is inserted into
     * the Repository or false on fail.
     * @param $request
     * @return bool
     */
    public function addNewUser($request)
    {
        $name = $request->getParameter('email');
        $type = $request->getParameter('role');
        $password = "";

        $entity = new User();
        $entity->setRepositoryManager($this->repositoryManager);
        $entity->setName($name);
        $entity->setPassword($password);
        $entity->setRole($type);

        return $entity->save();
    }
    /**
     * This function returns true if a user is deleted from
     * the Repository or null on fail.
     * @param $request
     * @return bool
     * @throws \Exception
     */
    //TODO PSR!!!
    public function delete($request) {
        $repository = $this->repositoryManager->getRepository(User::class);
        $id = $request->getRequestParameters()['id'];

        //TODO add try catch like in ResultsService
        $entity = $repository->find((int)$id);

        return $entity->remove();
    }
    /**
     * This function returns the entity of the user with the provided id.
     * @param RequestInterface $request
     * @return \ReallyOrm\Entity\EntityInterface|null
     * @throws \Exception
     */
    public function getUpdatePageParams(RequestInterface $request)
    {
        $id = $request->getRequestParameters()['id'];
        $repository = $this->repositoryManager->getrepository(User::class);

        //TODO add try catch like in ResultsService
        return $repository->find((int)$id);
    }
    /**
     * This function update info about the user with provided id.
     * true is returned if the user is updated, false if failed.
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function updateEntity($request)
    {
        $repository = $this->repositoryManager->getRepository(User::class);
        $name = $request->getParameter('email');
        $role = $request->getParameter('role');

        $id = $request->getRequestParameters()['id'];
        //TODO add try catch like in ResultsService
        $entity = $repository->find((int)$id);

        $entity->setName($name);
        $entity->setRole($role);

        return $entity->save();
    }
    /**
     * This function gets the $page page of user entities from user repository
     * for all the users with the role like $role. If no entities exist, it return null.
     * @param string $role
     * @param int $page
     * @return mixed
     */
    public function findWildCard(string $role, int $page): ?array
    {
        try {
            $repository = $this->repositoryManager->getRepository(User::class);
        } catch (NoSuchRepositoryException $e) {
            return null;
        }

        $page = ($page - 1) * $this::RESULTS_PER_PAGE;
        try {
            $entities = $repository->findBy(['role' => $role], [], $this::RESULTS_PER_PAGE, $page);
        } catch (NoSuchRowException $e) {
            $entities = null;
        }

        return $entities;
    }
}
