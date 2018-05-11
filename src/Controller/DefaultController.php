<?php

namespace WonderKind\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", defaults={"_format"="html"}, methods={"GET"}, name="main_form")
     *
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

}