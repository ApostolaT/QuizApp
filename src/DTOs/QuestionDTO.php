<?php


namespace QuizApp\DTOs;

/**
 * Class QuestionDTO
 *
 * this class is used for data transfer between
 * controller and admin-score and admin-results
 * FE pages
 * @package QuizApp\DTOs
 */
class QuestionDTO
{
    /**
     * The text of the questionInstance
     * @var string
     */
    private $questionText;

    /**
     * The text of the AnswerInstance where
     * AnswerInstance can be of type TextInstance
     * @var string
     */
    private $answerText;

    public function __construct(string $questionText, string $answerText)
    {
        $this->questionText = $questionText;
        $this->answerText = $answerText;
    }

    public function getQuestionText()
    {
        return $this->questionText;
    }

    public function setQuestionText(string $question)
    {
        $this->questionText = $question;
    }

    public function getAnswerText()
    {
        return $this->answerText;
    }

    public function setAnswerTexts(string $answer)
    {
        $this->answerText = $answer;
    }
}