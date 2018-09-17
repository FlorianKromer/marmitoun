<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recette;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $recettes = $this->getDoctrine()
        ->getRepository(Recette::class)
        ->findAll();
        return $this->render('default/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    /**
     * @Route("/detail/{slug}", name="recette_show")
     */
    public function show($slug)
    {
        $recette = $this->getDoctrine()
        ->getRepository(Recette::class)
        ->findOneBySlug($slug);

        if (!$recette) {
            throw $this->createNotFoundException(
                'No recette found for id '.$slug
            );
        }
        return $this->render('default/detail.html.twig', [
            'recette' => $recette,
        ]);
    }
}
