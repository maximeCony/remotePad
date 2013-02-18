<?php

namespace RemotePad\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomeController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('RemotePadMainBundle:Home:index.html.twig');
    }
    
    public function loggedAction()
    {
        return $this->render('RemotePadMainBundle:Security:logged.html.twig');
    }
    
    public function loginHeaderAction()
    {
        return $this->render('RemotePadMainBundle:Security:loginHeader.html.twig');
    }
}
