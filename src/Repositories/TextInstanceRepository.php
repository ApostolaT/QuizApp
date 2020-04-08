<?php


namespace QuizApp\Repositories;


use ReallyOrm\Repository\AbstractRepository;

class TextInstanceRepository extends AbstractRepository
{
    /**
     * @inheritDoc
     */
    public function getSearchableFields(): array
    {
        return ["text"];
    }
}