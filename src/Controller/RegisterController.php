<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'users_ajax_create')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, MailerInterface $mailer): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user, [
            'action' => $this->generateUrl('homepage'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordHash = $userPasswordHasher->hashPassword($user, (string) $user->plainPassword);
            $user->setPassword($passwordHash);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->sendConfirmationEmail($user, $mailer);

            $this->addFlash('success', 'message.add_success_user');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('login/register.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    private function sendConfirmationEmail(User $user, MailerInterface $mailer): void
    {
        $email = (new TemplatedEmail())
            ->from('bikepoint@e-commit.fr')
            ->to(new Address((string) $user->getEmail()))
            ->subject('Confirmation de compte')
            ->htmlTemplate('emails/account.html.twig')
            ->context([
                'user' => $user,
            ]);

        $mailer->send($email);
    }
}
