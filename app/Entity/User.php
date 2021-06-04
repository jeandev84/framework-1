<?php
namespace App\Entity;


/**
 * Class User
 * @package App\Entity
*/
class User
{
    /**
     * @var int
     */
    private $id;


    /**
     * @var string
     */
    private $email;


    /**
     * @var string
     */
    private $password;



    /**
     * @var array
    */
    private $roles = [];



    /**
     * Post constructor.
    */
    public function __construct()
    {
    }


    /**
     * @return int
    */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }



    /**
     * @param string|null $email
     * @return User
    */
    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }


    /**
     * @param string|null $password
     * @return User
    */
    public function setPassword(?string $password): User
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @param array $roles
     * @return $this
    */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }



    /**
     * @return array
    */
    public function getRoles(): array
    {
        return $this->roles;
    }
}