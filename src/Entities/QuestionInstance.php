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
    private $text;

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
    public function setQuizInstanceId(string $quizInstanceId)
    {
        $this->quizInstanceId = $quizInstanceId;
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
}