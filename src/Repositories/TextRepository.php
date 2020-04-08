<?php


namespace QuizApp\Repositories;


use ReallyOrm\Repository\AbstractRepository;

class TextRepository extends AbstractRepository
{
    /**
     * @inheritDoc
     */
    public function getSearchableFields(): array
    {
        return ["text"];
    }
}