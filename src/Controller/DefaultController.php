<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Recette;
use App\EventSubscriber\AvisCreatedEvent;
use Knp\Component\Pager\PaginatorInterface;
//avis + event observer/observable
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
//pagination
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//form type import
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

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
     * @Route("/liste_recettes", name="liste_recettes")
     */
    public function listeRecettes(PaginatorInterface $paginator, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT a FROM App:Recette a';
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        // parameters to template
        return $this->render('default/listRecettes.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/detail/{slug}", name="recette_show")
     */
    public function show(Request $request, $slug)
    {
        $recette = $this->getDoctrine()
        ->getRepository(Recette::class)
        ->findOneBySlug($slug);

        if (!$recette) {
            throw $this->createNotFoundException(
                'No recette found for id '.$slug
            );
        }
        $avis = new Avis();
        $avis->setRecette($recette);
        $form = $this->createFormBuilder($avis)
        ->add('pseudo')
        ->add('contenu', TextareaType::class)
        ->add('email', EmailType::class)
        ->add('send', SubmitType::class)
        ->getForm();
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($avis);

                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();
                // creates the OrderPlacedEvent and dispatches it
                $event = new AvisCreatedEvent($avis);

                $this->eventDispatcher->dispatch(AvisCreatedEvent::NAME, $event);

                return $this->redirect($request->getUri());
            }
        } else {
            return $this->render('default/detail.html.twig', [
                'recette' => $recette,
                'form' => $form->createView(),
                ]);
        }
    }
}
