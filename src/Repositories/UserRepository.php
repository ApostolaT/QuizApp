<?php

namespace QuizApp\Repositories;

use ReallyOrm\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    /**
     * This function calls getFindLikeQueryResults() for all
     * the users with specified role from database and returns
     * the entities hydrated with the data from the call.
     * @param string $role
     * @param $page
     * @param $limit
     * @return array
     */
    public function selectAllLikeAndFrom(string $role, $page, $limit): array
    {
        $offset = ($page - 1) * $limit;
        $results = $this->getFindLikeQueryResults($role, $offset, $limit);

        $entities = [];
        foreach ($results as $result) {
            $entity = $this->hydrator->hydrate($this->getEntityName(), $result);
            $this->hydrator->hydrateId($entity, $result["id"]);
            $entities[] = $entity;
        }

        return $entities;
    }
    /**
     * This function counts all the user rows from the database
     * that have the role column like $role
     * @param string $role
     * @return mixed
     */
    public function countAllLike(string $role): array
    {
        $tableName = $this->createTableName();
        $query = $this->pdo->prepare("SELECT COUNT(*) as rows FROM $tableName WHERE role LIKE :role");
        $query->bindParam(":role", $role);
        $query->execute();

        return $query->fetch();
    }
    /**
     * This function creates and executes the query that selects all the users
     * from $offset offset with $limit limit that have the role column like $role
     * @param string $role
     * @param int $offset
     * @param int $limit
     * @return array
     */
    private function getFindLikeQueryResults(string $role, int $offset, int $limit): array
    {
        $tableName = $this->createTableName();

        $queryString =
            "SELECT * FROM $tableName WHERE role like :role LIMIT :limit OFFSET :offset";
        $query = $this->pdo->prepare($queryString);
        $query->bindParam(':role', $role);
        $query->bindParam(':limit', $limit);
        $query->bindParam(':offset', $offset);
        $query->execute();

        return $query->fetchAll();
    }
}