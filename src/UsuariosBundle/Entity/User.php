<?php

namespace UsuariosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UsuariosBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 16,
     *      minMessage = "El campo username debe tener como mínimo 4 caracterees",
     *      maxMessage = "El campo username debe tener como máximo 16 caracterees"
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;
    
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=4096)
     */
    private $plainPassword;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="roles", type="json_array")
     */
    private $roles = array();
    

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Set roles
     *
     * @param string $roles
     *
     * @return User
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        
        //allows for chaining

        return $this;
    }  
    

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     *
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     *
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getPlainPassword()
        {
            return $this->plainPassword;
        }

    public function setPlainPassword($password)
        {
            $this->plainPassword = $password;
        }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    //Métodos que debe implementar el Entity por UserInterface:
    
    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }
    
    public function getRoles()
    {
        return $this->roles;      //ASÍ ESTÁ EN LA DOCUMENTACIÓN OFICIAL DE SYMFONY
        //return array('ROLE_USER');      //ASÍ ESTÁ EN LA DOCUMENTACIÓN DEL API DE SYMFONY
    }
    
    public function eraseCredentials()
    {
    }
}

