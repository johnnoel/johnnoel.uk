<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\ContactType;
use App\Message\ContactNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route(
     *     "/contact.{_format}",
     *     name="contact",
     *     methods={"GET","POST"},
     *     defaults={"_format": "html"}, requirements={"_format": "|json"}
     * )
     */
    public function contact(Request $request): Response
    {
        $successMessage = null;
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dispatchMessage(ContactNotification::createFromModel($form->getData()));
            $successMessage = 'Yup, that sent';
        }

        if ($request->getRequestFormat() === 'json') {
            // render JSON form errors
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView(),
            'success_message' => $successMessage,
        ]);
    }
}
