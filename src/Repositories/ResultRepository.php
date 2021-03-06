<?php


namespace QuizApp\Repositories;


use ReallyOrm\Repository\AbstractRepository;

class ResultRepository extends AbstractRepository
{
    /**
     * @inheritDoc
     */
    public function getSearchableFields(): array
    {
        return ["name", "score"];
    }
}