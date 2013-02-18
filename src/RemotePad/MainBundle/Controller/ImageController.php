<?php

namespace RemotePad\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use RemotePad\MainBundle\Entity\Image;
use ArrayObject;

class ImageController extends Controller {

    public function getAction($id) {

        $response = new Response();

        $image = $this->getDoctrine()
                ->getRepository('RemotePadMainBundle:Image')
                ->find($id);

        if (!$image) {
            $response->setStatusCode(404);
        } else {
            $absolutePath = $image->getAbsolutePath();

            $response->setContent(file_get_contents($absolutePath));
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'image');
        }



        return $response;
    }

    public function uploadAction() {

        $image = new Image();
        $form = $this->createFormBuilder($image, array('csrf_protection' => false,))
                //->add('name')
                ->add('file')
                ->getForm();

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());

            $responseContent = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
            $responseContent->resp = null;
            $responseContent->err = null;

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($image);
                $em->flush();

                $filename = $image->getId() . "." . $image->getPath();

                $responseContent->resp['path'] = $this->generateUrl("RemotePadMainBundle_homepage") . "../storage/remotes/images/$filename";
                $responseContent->resp['imageId'] = $image->getId();
            } else {
                $responseContent->err = "InvalidImage";
            }

            return new Response(json_encode($responseContent));
        }

        return $this->render('RemotePadMainBundle:Image:upload.html.twig', array(
                    'form' => $form->createView(),
                ));
    }

}

