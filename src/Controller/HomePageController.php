<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="homepage_index")
     */
    public function index()
    {
        return $this->redirectToRoute('article_index', ['_locale' => 'en']);
    }
}