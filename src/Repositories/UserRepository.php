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
}