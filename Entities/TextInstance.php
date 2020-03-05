<?php

namespace quizapp\Entities;

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
    public function getQuestionInstanceId()
    {
        return $this->questionInstanceId;
    }

    /**
     * @ORM
     */
    public function setQuestionInstanceId(int $questionInstanceId)
    {
        $this->questionInstanceId = $questionInstanceId;
    }

}