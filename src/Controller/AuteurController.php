<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteurController extends AbstractController
{
    /**
     * @Route("/ajout-auteur", name="auteur_ajout")
     */
    public function ajout(ManagerRegistry $doctrine, Request $request): Response
    {
        $auteur = new Auteur();

        $form = $this->createForm(AuteurType::class, $auteur);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {
            $manager = $doctrine->getManager();
            $manager->persist($auteur);
            $manager->flush();

            return $this->redirectToRoute("app_auteurs");
        }

        return $this->render('auteur/formulaire.html.twig', [
            'formAuteur' => $form->createView()
        ]);
    }


    /**
     * @Route("/auteurs", name="app_auteurs")
     */
    public function allAuteurs(ManagerRegistry $doctrine)
    {
        $auteurs = $doctrine->getRepository(Auteur::class)->findAll();

        return $this->render("auteur/allAuteurs.html.twig", [
            "auteurs" => $auteurs
        ]);
    }


    /**
     * @Route("/auteur_update_{id<\d+>}", name="auteur_update")
     */
    public function update(ManagerRegistry $doctrine, Request $request, $id)
    {
        $auteur = $doctrine->getRepository(Auteur::class)->find($id);

        $form = $this->createForm(AuteurType::class, $auteur);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {
            $manager = $doctrine->getManager();
            $manager->persist($auteur);
            $manager->flush();

            return $this->redirectToRoute("app_auteurs");
        }

        return $this->render('auteur/formulaire.html.twig', [
            'formAuteur' => $form->createView()
        ]);

    }


    /**
     * @Route("/auteur_delete_{id<\d+>}", name="auteur_delete")
     */
    public function delete(ManagerRegistry $doctrine, $id)
    {
        $auteur = $doctrine->getRepository(Auteur::class)->find($id);

        $manager = $doctrine->getManager();
        $manager->remove($auteur);
        $manager->flush();

        return $this->redirectToRoute("app_auteurs");
    }

    /**
     * @Route("auteur_{id<\d+>}", name="app_auteur")
     */
    public function unAuteur($id, ManagerRegistry $doctrine)
    {
        $auteur = $doctrine->getRepository(Auteur::class)->find($id);

        return $this->render("auteur/unAuteur.html.twig", [
            'auteur' => $auteur
        ]);
    }
}
