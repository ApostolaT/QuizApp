<?php

namespace QuizApp\Services;

use Exception;
use Psr\Http\Message\RequestInterface;
use QuizApp\Contracts\RowsCountInterface;
use QuizApp\Entities\User;
use ReallyOrm\Exceptions\NoSuchRepositoryException;
use ReallyOrm\Repository\RepositoryManagerInterface;
use ReallyOrm\Test\Repository\RepositoryManager;

class UserService implements RowsCountInterface
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
     * @param string $filter
     * @param string $searchValue
     * @param string $sortParam
     * @return array|null
     */
    public function getAll(int $page, string $filter = "", string $searchValue = "", $sortParam = ""): ?array
    {
        $offset = ($page - 1) * $this::RESULTS_PER_PAGE;

        try {
            $repository = $this->repositoryManager->getRepository(User::class);
            $entities = $repository->findAll(
                $filter,
                $searchValue,
                $sortParam,
                self::RESULTS_PER_PAGE,
                $offset
            );
        } catch (Exception $e) {
            $entities = null;
        }

        return $entities;
    }

    /**
     * This function counts all the user entities from the user repository
     * that match the filters
     * @param string $filterParameter
     * @param string $searchParameter
     * @return int
     */
    public function countRows(string $filterParameter = "", string $searchParameter = ""): int
    {
        $filters = ($filterParameter) ? ["role" => $filterParameter] : [];

        try {
            $userRepository = $this->repositoryManager->getRepository(User::class);
        } catch (NoSuchRepositoryException $e) {
            return 0;
        }

        return $userRepository->countRowsBy($filters, $searchParameter);
    }

    /**
     * This function returns true if a user is inserted into
     * the Repository or false on fail.
     * @param string $email
     * @param string $role
     * @return bool
     */
    public function addNewUser(string $email, string $role)
    {
        $password = "";

        $entity = new User();
        $entity->setRepositoryManager($this->repositoryManager);
        $entity->setName($email);
        $entity->setPassword($password);
        $entity->setRole($role);

        return $entity->save();
    }

    /**
     * This function returns true if a user is deleted from
     * the Repository or null on fail.
     * @param string $id
     * @return bool
     * @throws NoSuchRepositoryException
     */
    public function delete(string $id)
    {
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
    public function updateUser(string $name, string $role, string $id)
    {
        $repository = $this->repositoryManager->getRepository(User::class);
        //TODO add try catch like in ResultsService
        $entity = $repository->find((int)$id);

        $entity->setName($name);
        $entity->setRole($role);

        return $entity->save();
    }
}
