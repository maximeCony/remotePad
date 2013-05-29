<?php

namespace RemotePad\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use RemotePad\MainBundle\Entity\Remote;
use RemotePad\MainBundle\Entity\Button;
use RemotePad\MainBundle\Entity\Image;
use ArrayObject;
use SimpleXMLElement;

class RemoteController extends Controller {

    //number of remotes per page
    private $rowPerPage = 9;

    /*
    * Serve the create Remote page
    */
    public function createAction() {

        $categories = $this->getDoctrine()
        ->getRepository('RemotePadMainBundle:RemoteCategory')
        ->findAll();

        return $this->render('RemotePadMainBundle:Remote:create.html.twig', array(
            'categories' => $categories,
            ));
    }

    /*
    * Serve the list remotes page
    */
    public function listAction($page) {

        //get remotes by page
        $remotes = $this->getDoctrine()
        ->getRepository('RemotePadMainBundle:Remote')
        ->getRemotesByPage($page, $this->rowPerPage);

        //count remotes
        $countRemotes = $this->getDoctrine()
        ->getRepository('RemotePadMainBundle:Remote')
        ->countRemotes();
        //get the max number of pages
        $maxPageNumber = ceil($countRemotes / $this->rowPerPage);

        return $this->render('RemotePadMainBundle:Remote:list.html.twig', array(
            'remotes' => $remotes, 
            'page' => $page, 
            'maxPageNumber' => $maxPageNumber,
            ));
    }

    /*
    * Serve the list user's remotes page
    */
    public function listMineAction($page) {

        $remotes = null;
        //get logged user
        $user = $this->get('security.context')->getToken()->getUser();
        $maxPageNumber = 0;

        //if there is no logged user
        if (!$user) {

            $to = ($page * $this->rowPerPage);
            $offset = $to - $this->rowPerPage;

            $countRemotes = $user->getRemotes()->count();
            $remotes = $user->getRemotes()->slice($offset, $to);

            $maxPageNumber = ceil($countRemotes / $this->rowPerPage);

            return $this->render('RemotePadMainBundle:Remote:list.html.twig', array(
                'remotes' => $remotes, 
                'page' => $page, 
                'maxPageNumber' => $maxPageNumber,
                ));
        }
        return $this->redirect($this->generateUrl('Remote_list'));        
    }

    /*
    * Serve the remote page
    */
    public function showAction($id) {

        $remote = $this->getDoctrine()
        ->getRepository('RemotePadMainBundle:Remote')
        ->find($id);

        if (!$remote) {
            return new Response("No Remote found. id: " . $id, 404);
        }

        return $this->render('RemotePadMainBundle:Remote:show.html.twig', array('remote' => $remote));
    }

    /*
    * Serve the edit remote page
    */
    public function editAction($id) {

        $remote = $this->getDoctrine()
        ->getRepository('RemotePadMainBundle:Remote')
        ->find($id);

        if (!$remote) {
            return new Response("No Remote found. id: " . $id, 404);
        }

        $categories = $this->getDoctrine()
        ->getRepository('RemotePadMainBundle:RemoteCategory')
        ->findAll();

        return $this->render('RemotePadMainBundle:Remote:edit.html.twig', array(
            'remote' => $remote,
            'categories' => $categories,
            ));
    }

    /*
    * Remove a remote
    */
    public function removeAction($id) {

        $em = $this->getDoctrine()->getEntityManager();
        $remote = $em->getRepository('RemotePadMainBundle:Remote')
        ->find($id);

        if (!$remote) {
            return new Response("No Remote found. id: " . $id, 404);
        }

        $em->remove($remote);
        $em->flush();
        return $this->redirect($this->generateUrl('Remote_list'));
    }

    /*
    * Get remote as xml
    */
    public function getXmlAction($id) {

        $remote = $this->getDoctrine()
        ->getRepository('RemotePadMainBundle:Remote')
        ->find($id);

        if (!$remote) {
            return new Response("No Remote found. id: " . $id, 404);
        }
        return $this->render('RemotePadMainBundle:Remote:remote.xml.twig', array('remote' => $remote));
    }

    /*
    * Update the remote
    */
    public function updateAction(Request $request, $remoteId) {

        $remote = $this->getDoctrine()
        ->getRepository('RemotePadMainBundle:Remote')
        ->find($remoteId);

        $response = new Response();

        if (!$remote) {
            return new Response("No Remote found. id: " . $id, 404);
        }

        $remoteJson = $request->request->get("remote", null);
        $name = $request->request->get("name", null);
        $description = $request->request->get("description", null);

        $responseContent = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
        $responseContent->resp = null;
        $responseContent->err = null;

        $em = $this->getDoctrine()->getEntityManager();

        if ($remoteJson != null) {

            if ($name != null) {

                $remoteObject = json_decode($remoteJson);
                if ($remoteObject != null) {

                    $buttons = $remote->getButtons();

                    foreach ($buttons as $button) {
                        $em->remove($button);
                    }
                    $em->flush();
                    $remote->removeButtons();

                    $user = $this->get('security.context')->getToken()->getUser();

                    if (!is_string($user)) {

                        $remote->setName($name);
                        $remote->setOwner($user);
                        $remote->setDescription($description);

                        foreach ($remoteObject as $buttonObject) {

                            $button = new Button();

                            if ($buttonObject->imageId != null) {
                                $image = $this->getDoctrine()
                                ->getRepository('RemotePadMainBundle:Image')
                                ->find($buttonObject->imageId);

                                if ($image)
                                    $button->setImage($image);
                            }

                            $button->setX($buttonObject->x);
                            $button->setY($buttonObject->y);
                            $button->setWidth($buttonObject->width);
                            $button->setHeight($buttonObject->height);
                            $button->setShortcut($buttonObject->shortcut);
                            $button->setRemote($remote);

                            $remote->addButton($button);
                        }

                        $em->persist($remote);
                        $em->flush();
                    } else {
                        $responseContent->err = "NoUserSpecified";
                        $response->setStatusCode(500);
                    }
                } else {
                    $responseContent->err = "InvalidRemote";
                    $response->setStatusCode(500);
                }

                $responseContent->resp = "success";
            } else {
                $responseContent->err = "NoNameSpecifed";
                $response->setStatusCode(500);
            }
        } else {
            $responseContent->err = "NoRemoteSpecifed";
            $response->setStatusCode(500);
        }

        $response->setContent(json_encode($responseContent));

        return $response;
    }

    public function saveAction(Request $request) {

        $response = new Response();

        $remoteJson = $request->request->get("remote", null);
        $name = $request->request->get("name", null);
        $description = $request->request->get("description", null);

        $responseContent = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
        $responseContent->resp = null;
        $responseContent->err = null;

        if ($remoteJson != null) {

            if ($name != null) {

                $remoteObject = json_decode($remoteJson);
                if ($remoteObject != null) {

                    $remote = new Remote();

                    $user = $this->get('security.context')->getToken()->getUser();

                    if (!is_string($user)) {

                        $remote->setName($name);
                        $remote->setOwner($user);
                        $remote->setDescription($description);

                        foreach ($remoteObject as $buttonObject) {

                            $button = new Button();

                            if ($buttonObject->imageId != null) {
                                $image = $this->getDoctrine()
                                ->getRepository('RemotePadMainBundle:Image')
                                ->find($buttonObject->imageId);

                                if ($image)
                                    $button->setImage($image);
                            }

                            $button->setX($buttonObject->x);
                            $button->setY($buttonObject->y);
                            $button->setWidth($buttonObject->width);
                            $button->setHeight($buttonObject->height);
                            $button->setShortcut($buttonObject->shortcut);
                            $button->setRemote($remote);

                            $remote->addButton($button);
                        }

                        $em = $this->getDoctrine()->getEntityManager();
                        $em->persist($remote);
                        $em->flush();

                        $user->addRemote($remote);
                        $userManager = $this->container->get('fos_user.user_manager');
                        $userManager->updateUser($user);
                    } else {
                        $responseContent->err = "NoUserSpecified";
                        $response->setStatusCode(500);
                    }
                } else {
                    $responseContent->err = "InvalidRemote";
                    $response->setStatusCode(500);
                }

                $responseContent->resp = "success";
            } else {
                $responseContent->err = "NoNameSpecifed";
                $response->setStatusCode(500);
            }
        } else {
            $responseContent->err = "NoRemoteSpecifed";
            $response->setStatusCode(500);
        }

        $response->setContent(json_encode($responseContent));

        return $response;
    }

}
