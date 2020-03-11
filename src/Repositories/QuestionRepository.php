<?php


namespace QuizApp\Repositories;


use ReallyOrm\Repository\AbstractRepository;

class QuestionRepository extends AbstractRepository
{
    public function getLastInsertedId(): string
    {
        return $this->pdo->lastInsertId();
    }
}