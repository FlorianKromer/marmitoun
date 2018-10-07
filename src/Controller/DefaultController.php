<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Recette;
use App\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Knp\Component\Pager\PaginatorInterface;

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
     * @Route("/liste_recettes", name="liste_recettes")
     */
    public function listeRecettes(PaginatorInterface $paginator,Request $request)
    {
        $em    = $this->getDoctrine()->getManager();
        $dql   = "SELECT a FROM App:Recette a";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        // parameters to template
        return $this->render('default/listRecettes.html.twig', array('pagination' => $pagination));
        // $recettes = $this->getDoctrine()
        // ->getRepository(Recette::class)
        // ->findAll();
        // return $this->render('default/index.html.twig', [
        //     'recettes' => $recettes,
        // ]);
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

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $contact = new Contact();
        $form = $this->createFormBuilder($contact)
        ->add('name')
        ->add('subject')
        ->add('content',TextareaType::class)
        ->add('mail', EmailType::class)
        ->add('tel', TelType::class)
        ->add('send', SubmitType::class)
        ->getForm();

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $message = (new \Swift_Message('Message de '. $contact->getName()))
                ->setFrom($contact->getMail())
                ->setTo(getenv('SEND_TO'))
                ->setReplyTo($contact->getMail())
                ->setBody($this->renderView('mail/mail_contact.html.twig', array('contact' => $contact)),'text/html')
                ;
                $mailer->send($message);
                $this->addFlash('notice', 'Votre message a bien été envoyé');
            }
        }
        return $this->render('default/contact.html.twig',array('form' => $form->createView()));
    }
}
