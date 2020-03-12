<?php

namespace QuizApp\Repositories;

use ReallyOrm\Repository\AbstractRepository;

class QuestionInstanceRepository extends AbstractRepository
{
    public function countInstancesWithQuestionInstance(string $quizInstanceId)
    {
        $queryString =
            'SELECT COUNT(quizInstanceId)
            FROM questionInstance WHERE quizInstanceId = :quizInstanceId';
        $query = $this->pdo->prepare($queryString);
        $query->bindParam(":quizInstanceId", $quizInstanceId);
        $query->execute();

        return $query->fetch();
    }
}