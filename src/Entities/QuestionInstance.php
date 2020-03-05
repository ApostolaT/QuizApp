<?php

namespace QuizApp\Entities;

use ReallyOrm\Entity\AbstractEntity;

class QuestionInstance extends AbstractEntity
{
    /**
     * @ORM
     */
    private $quizInstanceId;

    /**
     * @ORM
     */
    private $questionTemplateId;

    /**
     * @ORM
     */
    private $text;

    /**
     * @ORM
     */
    private $type;

    /**
     * @ORM
     */
    public function getQuizInstanceId()
    {
        return $this->quizInstanceId;
    }

    /**
     * @ORM
     */
    public function setQuizInstanceId(int $quizInstanceId)
    {
        $this->quizInstanceId = $quizInstanceId;
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
    public function setQuestionTemplateId(int $questionTemplateId)
    {
        $this->questionTemplateId = $questionTemplateId;
    }

    /**
     * @ORM
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @ORM
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @ORM
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @ORM
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }
}
