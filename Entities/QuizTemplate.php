<?php


namespace quizapp\Entities;

use ReallyOrm\Entity\AbstractEntity;

class QuizTemplate extends AbstractEntity
{
    /**
     * @ORM
     */
    private $name;

    /**
     * @ORM
     */
    private $createdBy;

    /**
     * @ORM
     */
    private $type;

    /**
     * @ORM
     */
    public function setCreatedBy(string $createdBy)
    {
        $this->createdBy = $createdBy;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @ORM
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
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