<?php

namespace RemotePad\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="rp_remote")
 * @ORM\Entity(repositoryClass="RemotePad\MainBundle\Repository\RemoteRepository")
 */
class Remote {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
     /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Button", mappedBy="remote", cascade={"persist", "remove"})
     */
    private $buttons;
    
    /**
     * @ORM\ManyToOne(targetEntity="RemoteCategory")
     * @ORM\JoinColumn(name="remote_category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ownedRemotes")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="remotes")
     */
    private $users;

    public function __construct() {
        $this->buttons = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Add buttons
     *
     * @param RemotePad\MainBundle\Entity\Button $buttons
     */
    public function addButton(\RemotePad\MainBundle\Entity\Button $buttons) {
        $this->buttons[] = $buttons;
    }
    
    /**
     * Remove buttons
     */
    public function removeButtons() {
        $this->buttons = new ArrayCollection();
    }

    /**
     * Get buttons
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getButtons() {
        return $this->buttons;
    }

    /**
     * Set owner
     *
     * @param RemotePad\MainBundle\Entity\User $owner
     */
    public function setOwner(\RemotePad\MainBundle\Entity\User $owner) {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return RemotePad\MainBundle\Entity\User 
     */
    public function getOwner() {
        return $this->owner;
    }


    /**
     * Add users
     *
     * @param RemotePad\MainBundle\Entity\User $users
     */
    public function addUser(\RemotePad\MainBundle\Entity\User $users)
    {
        $this->users[] = $users;
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param RemotePad\MainBundle\Entity\RemoteCategory $category
     */
    public function setCategory(\RemotePad\MainBundle\Entity\RemoteCategory $category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return RemotePad\MainBundle\Entity\RemoteCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
}