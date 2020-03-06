<?php

namespace QuizApp\Entities;

use ReallyOrm\Entity\AbstractEntity;

class User extends AbstractEntity
{
    /**
     * @ORM
     */
    private $name;

    /**
     * @ORM
     */
    private $role;

    /**
     * @ORM
     */
    private $password;

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
    public function setRole(string $role)
    {
        $this->role = $role;
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
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @ORM
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @ORM
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
}
