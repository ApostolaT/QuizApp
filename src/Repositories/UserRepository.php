<?php

namespace QuizApp\Repositories;

use ReallyOrm\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    /**
     * This functions returns all the searchable fields from the user table
     * @return array
     */
    public function getSearchableFields(): array
    {
        return ["name"];
    }

    /**
     * @param string $filter
     * @param string $searchValue
     * @param string $sortParam
     * @param int $resultsPerPage
     * @param int $offset
     * @return array|\ReallyOrm\Entity\EntityInterface[]
     * @throws \ReallyOrm\Exceptions\NoSuchRowException
     */
    public function findAll(
        string $filter,
        string $searchValue,
        string $sortParam,
        int $resultsPerPage,
        int $offset
    ){
        $orderParam = ($sortParam !== "") ? ["name" => ($sortParam === "asc") ? "ASC" : "DESC"] : [];
        $filter = ($filter !== "") ? ["role" => $filter] : [];

        return $this->findBy(
            $filter,
            $searchValue,
            $orderParam,
            $resultsPerPage,
            $offset
        );
    }
}