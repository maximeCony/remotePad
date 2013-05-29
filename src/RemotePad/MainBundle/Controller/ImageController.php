<?php

namespace RemotePad\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use RemotePad\MainBundle\Entity\Image;
use ArrayObject;

class ImageController extends Controller {

    /*
    * Upload image
    */
    public function uploadAction() {

        $image = new Image();

        //create the form
        $form = $this->createFormBuilder($image, array('csrf_protection' => false,))
                ->add('file')
                ->getForm();

        if ($this->getRequest()->getMethod() === 'POST') {
            
            //bind request to the form
            $form->bindRequest($this->getRequest());

            $responseContent = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
            //will contain the response
            $responseContent->resp = null;
            //will contain the error
            $responseContent->err = null;

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();
                //save image
                $em->persist($image);
                $em->flush();
                //get filename
                $filename = $image->getId() . "." . $image->getPath();
                //set the path of the image
                $responseContent->resp['path'] = $this->generateUrl("RemotePadMainBundle_homepage") . "../storage/remotes/images/$filename";
                //set the image id
                $responseContent->resp['imageId'] = $image->getId();
            } else {
                $responseContent->err = "InvalidImage";
            }
            //return response
            return new Response(json_encode($responseContent));
        }
        //return the upload form
        return $this->render('RemotePadMainBundle:Image:upload.html.twig', array(
                    'form' => $form->createView(),
                ));
    }

    /*
    * Get image by id
    */
    public function getAction($id) {

        $response = new Response();

        $image = $this->getDoctrine()
                ->getRepository('RemotePadMainBundle:Image')
                ->find($id);

        if (!$image) {
            //if the image is not found
            $response->setStatusCode(404);
        } else {
            $absolutePath = $image->getAbsolutePath();
            //set the image as response content
            $response->setContent(file_get_contents($absolutePath));
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'image');
        }
        return $response;
    }

}

