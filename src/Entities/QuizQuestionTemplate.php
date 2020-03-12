<?php


namespace QuizApp\Entities;


use ReallyOrm\Entity\AbstractEntity;

class QuizQuestionTemplate extends AbstractEntity
{
    /**
     * @ORM
     */
    private $quizTemplateId;

    /**
     * @ORM
     */
    private $questionTemplateId;

    /**
     * @ORM
     */
    public function getQuizTemplateId()
    {
        return $this->quizTemplateId;
    }

    /**
     * @ORM
     */
    public function setQuizTemplateId(string $quizTemplateId): void
    {
        $this->quizTemplateId = $quizTemplateId;
    }

    /**
     * @ORM
     */
    public function getQuestionTemplateId()
    {
        return $this->questionTemplateId;
    }

    /**
     * @ORM
     */
    public function setQuestionTemplateId(string $questionTemplateId): void
    {
        $this->questionTemplateId = $questionTemplateId;
    }
}
