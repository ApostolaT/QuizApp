<?php


namespace QuizApp\Entities;


use ReallyOrm\Entity\AbstractEntity;

class TextInstance extends AbstractEntity
{
    /**
     * @ORM
     */
    private $questionInstanceId;

    /**
     * @ORM
     */
    private $text;

    /**
     * @ORM
     */
    public function getQuestionInstanceId()
    {
        return $this->questionInstanceId;
    }

    /**
     * @ORM
     */
    public function setQuestionInstanceId(string $questionInstanceId): void
    {
        $this->questionInstanceId = $questionInstanceId;
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
    public function setText(string $text): void
    {
        $this->text = $text;
    }
}