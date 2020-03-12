<?php


namespace QuizApp\Entities;


use ReallyOrm\Entity\AbstractEntity;

class QuizInstance extends AbstractEntity
{
    /**
     * @ORM
     */
    private $name;

    /**
     * @ORM
     */
    private $quizTemplateId;

    /**
     * @ORM
     */
    private $userId;

    /**
     * @ORM
     */
    private $currentQuestion;
    /**
     * @ORM
     */
    private $score;

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
    public function setQuizTemplateId(string $quizTemplateId)
    {
        $this->quizTemplateId = $quizTemplateId;
    }

    /**
     * @ORM
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @ORM
     */
    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @ORM
     */
    public function getCurrentQuestion()
    {
        return $this->currentQuestion;
    }

    /**
     * @ORM
     */
    public function setCurrentQuestion(string $currentQuestion)
    {
        $this->currentQuestion = $currentQuestion;
    }

    /**
     * @ORM
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @ORM
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @ORM
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @ORM
     */
    public function setScore(string $score): void
    {
        $this->score = $score;
    }
}