<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
       
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            
        ]);
    }
    /**
     *  @Route("/", name="home")
     */
    public function home(){
        $repo = $this->getDoctrine()->getRepository(Articles::class);
        $articles = $repo->findAll();
        return $this->render('blog/home.html.twig',[
            'articles' => $articles,
        ]
        );
    }
}
