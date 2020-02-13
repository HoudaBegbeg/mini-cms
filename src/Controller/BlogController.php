<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use App\Form\FormArticlesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $repo          = $this->getDoctrine()->getRepository(Articles::class);
        $articles      = $repo->findAll();
        return $this->render('blog/home.html.twig',[
            'articles' => $articles,
        ]
        );
    }
    /**
     *  @Route("/post/{id}", name="post")
     */
    public function post(Articles $id, Request $request, ArticlesRepository $articleRepository){
        $repo          = $this->getDoctrine()->getRepository(Articles::class);
        $article     = $repo->find($id);
        return $this->render('blog/post.html.twig',[
            'article' => $article,
        ]
        );
    }
    
    /**
     * @Route("/formulaire/{id}", name="formulaire", defaults={"id"= null})
     */
    public function edit(Articles $id=null, Request $request, ArticlesRepository $articleRepository)
    {

        $em         = $this->getDoctrine()->getManager();

        //If $article->id non connu dans la base de données creer un new article sinon recuperer l'ancien
        try {
            $article    = $articleRepository->find($id);
        } catch (\Throwable $th) {
            $article    = new Articles;

        }
        $form       = $this->createForm(FormArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();
            $em->persist($article);
            $em->flush();
            
           
        }
        return $this->render('blog/formulaire.twig', [
            'form' => $form->createView(),
            'articles'=>$article
        ]);
    
    }
     /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer(Articles $id, Request $request, ArticlesRepository $articleRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();
        return $this->render('blog/home.html.twig', [
            'articles'=>$articleRepository->findAll()
        ]);
    }
     /**
     * @Route("/editer/{id}", name="editer")
     */
    public function editer(Articles $article, Request $request, ArticlesRepository $articleRepository)
    {

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
        return $this->render('blog/home.html.twig', [
            'articles'=>$articleRepository->findAll()
        ]);
    }
}

