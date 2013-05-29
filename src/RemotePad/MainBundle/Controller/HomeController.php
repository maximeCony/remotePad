<?php

namespace RemotePad\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomeController extends Controller {
    
    /*
    * Serve main page
    */
    public function indexAction() {
        return $this->render('RemotePadMainBundle:Home:index.html.twig');
    }
    
    /*
    * Serve logged page
    */
    public function loggedAction() {
        return $this->render('RemotePadMainBundle:Security:logged.html.twig');
    }
    
    /*
    * Serve login page
    */
    public function loginHeaderAction() {
        return $this->render('RemotePadMainBundle:Security:loginHeader.html.twig');
    }
}
