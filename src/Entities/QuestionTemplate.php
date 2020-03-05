<?php

namespace QuizApp\Entities;

use ReallyOrm\Entity\AbstractEntity;

class QuestionTemplate extends AbstractEntity
{
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
    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @ORM
     */
    public function setType(string $type)
    {
        $this->type = $type;
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
    public function getType()
    {
        return $this->type;
    }
}
