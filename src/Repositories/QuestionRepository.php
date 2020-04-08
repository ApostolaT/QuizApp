<?php


namespace QuizApp\Repositories;


use ReallyOrm\Repository\AbstractRepository;

class QuestionRepository extends AbstractRepository
{
    /**
     * @inheritDoc
     */
    public function getSearchableFields(): array
    {
        return ["text"];
    }
}