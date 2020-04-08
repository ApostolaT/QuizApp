<?php


namespace QuizApp\Repositories;


use ReallyOrm\Repository\AbstractRepository;

class QuizTypeRepository extends AbstractRepository
{
    /**
     * @inheritDoc
     */
    public function getSearchableFields(): array
    {
        return ["name"];
    }
}