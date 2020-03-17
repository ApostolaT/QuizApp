<?php


namespace QuizApp\DTOs;


class QuestionDTO
{
    private $question;

    private $answer;

    public function __construct(string $question, string $answer)
    {
        $this->question = $question;
        $this->answer = $answer;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion(string $question)
    {
        $this->question = $question;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function setAnswer(string $answer)
    {
        $this->answer = $answer;
    }
}