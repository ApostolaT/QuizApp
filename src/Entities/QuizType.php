<?php


namespace QuizApp\Entities;


use ReallyOrm\Entity\AbstractEntity;

class QuizType extends AbstractEntity
{
    /**
     * @ORM
     */
    private $name;

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
    public function setName($name): void
    {
        $this->name = $name;
    }


}