<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="app_articles")
     */
    public function allArticles(ManagerRegistry $doctrine): Response
    {
        $articles = $doctrine->getRepository(Article::class)->findAll();

        //dd($articles);

        return $this->render('article/allArticles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article_{id<\d+>}", name="app_article")
     */
    public function showArticle($id, ManagerRegistry $doctrine)
    {
       $article = $doctrine->getRepository(Article::class)->find($id);

       return $this->render("article/unArticle.html.twig", [
           'article' => $article
       ]);
    }

    /**
     * @Route("/article_ajout", name="article_ajout")
     */
    public function ajout(ManagerRegistry $doctrine, Request $request )
    {
        // on crée un objet article
        $article = new Article();
        // en crée le formulaire en liant le FormType à l'objet crée
        $form = $this->createForm(ArticleType::class, $article);
        // on donne accés aux données du formulaire pour la validation des données
        $form->handleRequest($request);
        // si le focmulaire est soumis et valide
        if( $form->isSubmitted() && $form->isValid())
        {
            // je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
            $article->setDateDeCreation(new DateTime("now"));
            // on recupere le manager de doctrine
            $manager = $doctrine->getManager();
            // on persist l'objet
            $manager->persist($article);
            // puis en envoie en bdd
            $manager->flush();

            return $this->redirectToRoute("app_articles");
        }

        return $this->render("article/formulaire.html.twig", [
            'formArticle' => $form->createView()
        ]);
    }


    /**
     * @Route("/update-article/{id}", name="article_update")
     */
    public function update(ManagerRegistry $doctrine, $id, Request $request)// $id auras comme valeur l'id passé en paramétre de la route
    {
        // on récuper l'article dont l'id est celui passé en parametre de la fonction 
        $article = $doctrine->getRepository(Article::class)->find($id);

        //dd($article);

         // en crée le formulaire en liant le FormType à l'objet crée
         $form = $this->createForm(ArticleType::class, $article);
         // on donne accés aux données du formulaire pour la validation des données
         $form->handleRequest($request);
         // si le focmulaire est soumis et valide
         if( $form->isSubmitted() && $form->isValid())
         {
             // je m'occupe d'affecter les données manquantes (qui ne parviennent pas du formulaire)
             $article->setDateDeModification(new DateTime("now"));
             // on recupere le manager de doctrine
             $manager = $doctrine->getManager();
             // on persist l'objet
             $manager->persist($article);
             // puis en envoie en bdd
             $manager->flush();
 
             return $this->redirectToRoute("app_articles");
         }
 
         return $this->render("article/formulaire.html.twig", [
             'formArticle' => $form->createView()
         ]);

    }

    

    /**
     * @Route("/article_delete_{id<\d+>}", name="article_delete")
     */
    public function delete($id, ManagerRegistry $doctrine)
    {   
        // on récupere l'article à supprimer
        $article = $doctrine->getRepository(Article::class)->find($id);
        // on récupere le manager de doctrine
        $manager = $doctrine->getManager();
        //on prépare la suppression de l'article
        $manager->remove($article);
        // on execute l'action (suppression)
        $manager->flush();

        return $this->redirectToRoute("app_articles");

    }



}
