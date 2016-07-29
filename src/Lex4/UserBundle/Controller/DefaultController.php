<?php

namespace Lex4\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('Lex4UserBundle:Default:index.html.twig');
    }
}
