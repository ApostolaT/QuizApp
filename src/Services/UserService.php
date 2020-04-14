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
     * @param array $filters
     * @param string $searchValue
     * @return array|null
     */
    public function getAll(int $page, array $filters = [], string $searchValue = ""): ?array
    {
        $offset = ($page - 1) * $this::RESULTS_PER_PAGE;

        try {
            $repository = $this->repositoryManager->getRepository(User::class);
        } catch (NoSuchRepositoryException $e) {
            return null;
        }

        try {
            $entities = $repository->findBy($filters, $searchValue, [], self::RESULTS_PER_PAGE, $offset);
        } catch (NoSuchRowException $e) {
            $entities = null;
        }

        return $entities;
    }

    /**
     * This function counts all the user entities from the user repository
     * that match the filters
     * @param array $role
     * @param string $searchValue
     * @return int
     */
    public function countRows(array $role = [], string $searchValue = ""): int
    {
        try {
            $userRepository = $this->repositoryManager->getRepository(User::class);
        } catch (NoSuchRepositoryException $e) {
            return 0;
        }

        return $userRepository->countRowsBy($role, $searchValue);
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
     */
    public function delete($request)
    {
        $id = $request->getRequestParameters()['id'];
        $repository = $this->repositoryManager->getRepository(User::class);

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
}
