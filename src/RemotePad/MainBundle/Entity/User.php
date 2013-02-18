<?php

namespace RemotePad\MainBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="RemotePad\MainBundle\Repository\UserRepository")
 * @ORM\Table(name="rp_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Remote", inversedBy="users")
     * @ORM\JoinTable(name="users_remotes")
     */
    private $remotes;
    
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="owner")
     */
    protected $ownedRemotes;

    public function __construct() {
        parent::__construct();

        $this->remotes = new ArrayCollection();
        $this->ownedRemotes = new ArrayCollection();
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
     * Add remotes
     *
     * @param RemotePad\MainBundle\Entity\Remote $remotes
     */
    public function addRemote(\RemotePad\MainBundle\Entity\Remote $remotes) {
        $this->remotes[] = $remotes;
    }

    /**
     * Get remotes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRemotes() {
        return $this->remotes;
    }


    /**
     * Add ownedRemotes
     *
     * @param RemotePad\MainBundle\Entity\User $ownedRemotes
     */
    public function addUser(\RemotePad\MainBundle\Entity\User $ownedRemotes)
    {
        $this->ownedRemotes[] = $ownedRemotes;
    }

    /**
     * Get ownedRemotes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getOwnedRemotes()
    {
        return $this->ownedRemotes;
    }
}