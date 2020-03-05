<?php

namespace QuizApp\Entities;

use ReallyOrm\Entity\AbstractEntity;

class QuizInstance extends AbstractEntity
{
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
    private $score;

    /**
     * @ORM
     */
    private $type;

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
    public function setQuizTemplateId(int $quizTemplateId)
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
    public function setUserId(int $userId)
    {
        $this->userId = $userId;
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
    public function setScore(int $score)
    {
        $this->score = $score;
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
    public function setType(int $type)
    {
        $this->type = $type;
    }
}
