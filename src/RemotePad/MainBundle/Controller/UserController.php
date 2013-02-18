<?php

namespace RemotePad\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use RemotePad\MainBundle\Entity\User;
use RemotePad\MainBundle\Entity\Remote;

class UserController extends Controller {

    public function getAction(Request $request) {

        $response = new Response();

        $username = $request->query->get("username", null);
        $password = $request->query->get("password", null);

        if ($username != null && $password != null) {

            $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($username);

            if ($user) {

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $hashedPassword = $encoder->encodePassword($password, $user->getSalt());

                if ($user->getPassword() == $hashedPassword) {

                    $response = new Response($user->getId(), 200);
                } else {
                    $response = new Response('InvalidParameters', 403);
                }
            } else {
                $response = new Response('InvalidParameters', 403);
            }
        } else {
            $response = new Response('MissingParameters', 403);
        }
        return $response;
    }

    public function getRemoteListAction($userId) {

        $user = $this->getDoctrine()
                ->getRepository('RemotePadMainBundle:User')
                ->find($userId);

        if ($user) {
            $remotes = $user->getRemotes();
            return $this->render('RemotePadMainBundle:Remote:list.xml.twig', array('remotes' => $remotes));
        } else {
            $response = new Response('InvalidUserId', 404);
        }

        return $response;
    }

    public function addRemoteAction($remoteId) {

        $remote = $this->getDoctrine()
                ->getRepository('RemotePadMainBundle:Remote')
                ->find($remoteId);

        if ($remote) {

            $user = $this->get('security.context')->getToken()->getUser();

            if (!is_string($user)) {

                $user->addRemote($remote);

                $userManager = $this->container->get('fos_user.user_manager');

                try {
                    $userManager->updateUser($user);
                } catch (Exception $e) {
                    $response = new Response(json_encode($e), 500);
                }

                $response = new Response('success', 200);
            } else {
                $response = new Response('UnloggedUser', 403);
            }
        } else {
            $response = new Response('InvalidRemoteId', 404);
        }

        return $response;
    }

}
