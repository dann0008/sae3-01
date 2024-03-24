<?php

namespace App\Controller;

use App\Repository\StageAltRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request)
    {

        $email = (new Email())
            ->from($request->request->get('email'))
            ->to('admin@test.fr')
            ->subject($request->request->get('subject'))
            ->text($request->request->get('name').' : '.$request->request->get('message'));

        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailer = new Mailer($transport);

        $mailer->send($email);
        return $this->redirectToRoute('app_home');
    }

    #[Route('/contactUser', name: 'app_contact_user')]
    public function contactUser(Request $request, UtilisateurRepository $utilisateur)
    {
        $email = (new Email())
            ->from($this->getUser()->getEmail())
            ->to($request->request->get('destinataire'))
            ->subject($request->request->get('subject'))
            ->text($this->getUser()->getNom().' '.$this->getUser()->getPrenom().' : '.$request->request->get('message'));

        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailer = new Mailer($transport);
        $mailer->send($email);
        $this->addFlash('success', 'Votre message a été envoyé avec succès');
        return $this->render('utilisateur/show.html.twig', ['utilisateur' => $utilisateur->find($request->request->get('utilisateur'))]
        );
    }

    #[Route('/contactStage', name: 'app_contact_stage')]
    public function contactStage(Request $request)
    {
        $email = (new Email())
            ->from($this->getUser()->getEmail())
            ->to($request->request->get('destinataire'))
            ->subject($request->request->get('subject'))
            ->text($this->getUser()->getNom().' '.$this->getUser()->getPrenom().' : '.$request->request->get('message'));

        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailer = new Mailer($transport);
        $mailer->send($email);
        $this->addFlash('success', 'Votre message a été envoyé avec succès');
        return $this->redirect('stagealts/'.$request->request->get('utilisateur'));

    }

    #[Route('/contactTer', name: 'app_contact_ter')]
    public function contactTer(Request $request)
    {
        $email = (new Email())
            ->from($this->getUser()->getEmail())
            ->to($request->request->get('destinataire'))
            ->subject($request->request->get('subject'))
            ->text($this->getUser()->getNom().' '.$this->getUser()->getPrenom().' : '.$request->request->get('message'));

        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailer = new Mailer($transport);
        $mailer->send($email);
        $this->addFlash('success', 'Votre message a été envoyé avec succès');
        return $this->redirect('ter/'.$request->request->get('utilisateur'));

    }

    #[Route('/contactCode', name: 'app_contact_code')]
    public function contactCode(Request $request)
    {

        $email = (new Email())
            ->from($request->request->get('email'))
            ->to('admin@test.fr')
            ->subject('Mot de passe Oublié')
            ->text("L''adresse email : ".$request->request->get('email').' souhaite recuperer son compte.');

        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailer = new Mailer($transport);

        $mailer->send($email);
        return $this->redirectToRoute('app_home');
    }

    #[Route('/formulaireContact', name: 'app_form_user')]
    public function FormUser()
    {
        return $this->render('mailer/contactUser.html.twig');

    }
}
