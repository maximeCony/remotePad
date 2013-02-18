<?php

namespace RemotePad\MainBundle\Service;

use RemotePad\MainBundle\Entity\Remote;
use RemotePad\MainBundle\Entity\Button;
use RemotePad\MainBundle\Entity\Image;
use Doctrine\ORM\EntityManager;

class RemoteService {

    protected $remote;
    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Get Remote
     *
     * @return Remote 
     */
    public function getRemote() {
        return $this->remote;
    }

    /**
     * Set remote
     *
     * @param Remote $remote
     */
    public function setRemote(Remote $remote) {
        $this->remote = $remote;
    }

    /**
     * @return Image
     */
    public function buildRemote($remoteObject) {

        if (!isset($remoteObject->name)) {
            throw new \Exception('Remote name can not be empty');
        }
        if (!isset($remoteObject->user) || is_string($user) ) {
            throw new \Exception('Remote user can not be empty');
        }

        if (!isset($remoteObject->buttons) || !is_array($remoteObject->buttons)) {
            throw new \Exception('Remote must have buttons');
        }

        $remote = new Remote();
        $remote->setName($remoteObject->name);

        foreach ($remoteObject->buttons as $buttonObject) {

            $button = $this->buildButton($buttonObject);
            $button->setRemote($remote);
            $remote->addButton($button);
        }

        $this->remote = $remote;

        return $this;
    }

    /**
     * @return Button
     */
    public function buildButton($buttonObject) {

        if (!isset($buttonObject->x)) {
            throw new \Exception('Button must have an x param');
        }

        if (!isset($buttonObject->y)) {
            throw new \Exception('Button must have an y param');
        }

        if (!isset($buttonObject->width)) {
            throw new \Exception('Button must have an width param');
        }

        if (!isset($buttonObject->height)) {
            throw new \Exception('Button must have an height param');
        }

        $button = new Button();

        if ($buttonObject->imageId != null) {
            $image = $this->em->getRepository('RemotePadMainBundle:Image')
                    ->find($buttonObject->imageId);

            if ($image != null) {
                $button->setImage($image);
            }
        }

        $button->setX($buttonObject->x);
        $button->setY($buttonObject->y);
        $button->setWidth($buttonObject->width);
        $button->setHeight($buttonObject->height);
        if (isset($buttonObject->shortcut)) {
            $button->setShortcut($buttonObject->shortcut);
        }

        return $button;
    }

    public function persistRemote() {

        $this->em->persist($this->remote);
        $this->em->flush();
    }

}