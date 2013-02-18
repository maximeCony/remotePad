<?php

namespace RemotePad\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * YouFood\RestaurantBundle\Entity\Button
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Button {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float $x
     *
     * @ORM\Column(name="x", type="decimal", scale=2)
     */
    private $x;

    /**
     * @var float $x
     *
     * @ORM\Column(name="y", type="decimal", scale=2)
     */
    private $y;

    /**
     * @var float $height
     *
     * @ORM\Column(name="height", type="decimal", scale=2)
     */
    private $height;

    /**
     * @var float $width
     *
     * @ORM\Column(name="width", type="decimal", scale=2)
     */
    private $width;

    /**
     * @var string $shortcut
     *
     * @ORM\Column(name="shortcut", type="string", length=255)
     */
    private $shortcut;

    /**
     * @ORM\ManyToOne(targetEntity="Remote", inversedBy="buttons")
     * @ORM\JoinColumn(name="remote_id", referencedColumnName="id")
     */
    private $remote;

     /**
     * @ORM\ManyToOne(targetEntity="Image", inversedBy="buttons")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

   

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set x
     *
     * @param decimal $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * Get x
     *
     * @return decimal 
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param decimal $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * Get y
     *
     * @return decimal 
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set height
     *
     * @param decimal $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * Get height
     *
     * @return decimal 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set width
     *
     * @param decimal $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * Get width
     *
     * @return decimal 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set shortcut
     *
     * @param string $shortcut
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;
    }

    /**
     * Get shortcut
     *
     * @return string 
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }

    /**
     * Set remote
     *
     * @param RemotePad\MainBundle\Entity\Remote $remote
     */
    public function setRemote(\RemotePad\MainBundle\Entity\Remote $remote)
    {
        $this->remote = $remote;
    }

    /**
     * Get remote
     *
     * @return RemotePad\MainBundle\Entity\Remote 
     */
    public function getRemote()
    {
        return $this->remote;
    }

    /**
     * Set image
     *
     * @param RemotePad\MainBundle\Entity\Image $image
     */
    public function setImage(\RemotePad\MainBundle\Entity\Image $image)
    {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @return RemotePad\MainBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add image
     *
     * @param RemotePad\MainBundle\Entity\Image $image
     */
    public function addImage(\RemotePad\MainBundle\Entity\Image $image)
    {
        $this->image[] = $image;
    }
}