<?php

namespace NinjaThesaurus\ThesaurusBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NinjaThesaurusThesaurusBundle:Default:index.html.twig');
    }
}
