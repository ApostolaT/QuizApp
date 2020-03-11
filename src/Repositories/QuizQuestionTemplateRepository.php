<?php


namespace QuizApp\Repositories;


use ReallyOrm\Repository\AbstractRepository;

class QuizQuestionTemplateRepository extends AbstractRepository
{
    public function getLastInsertedId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function deleteById(int $id)
    {
        $query = $this->createQQTDeleteQuery($id);
        $query->execute();

        return $query->rowCount() > 0;
    }

    private function createQQTDeleteQuery(int $id)
    {
        $tableName = $this->createTableName();
        $queryString = "DELETE FROM $tableName WHERE quizTemplateId  = :id";
        $query = $this->pdo->prepare($queryString);
        $query->bindParam(":id", $id);

        return $query;
    }
}