<?php

namespace Main\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Template
     */
    public function indexAction()
    {
        return array('name' => 'persodic');
    }
}
