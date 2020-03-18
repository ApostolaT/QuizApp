<?php

namespace QuizApp\DTOs;

/**
 * Class QuizInstanceDTO
 * is a Data Transfer Object for a quizInstance because quizInstance contains just id's
 * but on a Front End page we need the name of the user and the name of the Quiz
 * @package QuizApp\DTOs
 */
class QuizInstanceDTO
{
    /**
     * id from QuizInstanceEntity
     * @var string
     */
    private $id;
    /**
     * userId from QuizInstanceEntity
     * @var string
     */
    private $userId;
    /**
     * quizTemplateId from QuizInstanceEntity
     * @var string
     */
    private $quizTemplateId;
    /**
     * quizName from QuizInstanceEntity
     * @var string
     */
    private $quizName;
    /**
     * name from User
     * @var string
     */
    private $userName;
    /**
     * score from QuizInstanceEntity
     * @var string
     */
    private $score;

    public function __construct(
        string $id,
        string $quizName,
        string $userId,
        string $quizTemplateId = "",
        string $userName = "",
        string $score = ""
    ) {
        $this->id = $id;
        $this->quizTemplateId = $quizTemplateId;
        $this->userId = $userId;
        $this->quizName = $quizName;
        $this->userName = $userName;
        $this->score = $score;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getQuizName(): string
    {
        return $this->quizName;
    }

    public function setQuizName(string $quizName): void
    {
        $this->quizName = $quizName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    public function setScore(string $score): void
    {
        $this->score = $score;
    }

    public function getQuizTemplateId(): string
    {
        return $this->quizTemplateId;
    }

    public function setQuizTemplateId(string $quizTemplateId)
    {
        $this->quizTemplateId = $quizTemplateId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }
}
