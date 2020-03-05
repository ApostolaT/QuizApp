<?php

namespace QuizApp\Entities;

use ReallyOrm\Entity\AbstractEntity;

class TextTemplate extends AbstractEntity
{
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
    private function setQuestionTemplateId(int $id)
    {
        $this->questionTemplateId = $id;
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
    public function getText()
    {
        return $this->text;
    }

    /**
     * @ORM
     */
    private function getQuestionTemplateId()
    {
        return $this->questionTemplateId;
    }
}
