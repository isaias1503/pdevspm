<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @var string
   *
   * @ORM\Column(name="name", type="string", length=255, nullable=false)
   */
  private $name;

  /**
   * @var string
   *
   * @ORM\Column(name="photo", type="string", length=64, nullable=true)
   */
  private $photo;

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Set name
   *
   * @param string $name
   * @return Users
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get name
   *
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set photo
   *
   * @param string $photo
   * @return User
   */
  public function setPhoto($photo)
  {
    $this->photo = $photo;

    return $this;
  }

  /**
   * Get photo
   *
   * @return string
   */
  public function getPhoto()
  {
    return $this->photo;
  }
}
