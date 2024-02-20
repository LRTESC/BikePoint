<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Ecommit\CrudBundle\Controller\AbstractCrudController;
use Ecommit\CrudBundle\Crud\Crud;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER_MANAGER')]
class UserAdminController extends AbstractCrudController
{
    protected function getCrudOptions(): array
    {
        $em = $this->container->get('doctrine')->getManager();
        $queryBuilder = $em->getRepository(User::class)->createQueryBuilder('u')
            ->select('u');

        $crudConfig = $this->createCrudConfig('users_admin_list');
        $crudConfig->addColumn(['id' => 'id', 'alias' => 'u.id', 'label' => 'label.id'])
            ->addColumn(['id' => 'firstName', 'alias' => 'u.firstName', 'label' => 'label.firstname'])
            ->addColumn(['id' => 'lastName', 'alias' => 'u.lastName', 'label' => 'label.lastname'])
            ->addColumn(['id' => 'birthDate', 'alias' => 'u.birthDate', 'label' => 'label.birth_date'])
            ->addColumn(['id' => 'email', 'alias' => 'u.email', 'label' => 'label.email'])
            ->addColumn(['id' => 'createdAt', 'alias' => 'u.createdAt', 'label' => 'label.created_at'])
            ->addColumn(['id' => 'enabled', 'alias' => 'u.enabled', 'label' => 'label.enabled'])
            ->addColumn(['id' => 'action', 'alias' => 'u.action', 'label' => 'label.action'])
            ->setQueryBuilder($queryBuilder)
            ->setMaxPerPage([2, 5, 10], 5)
            ->setDefaultSort('id', Crud::ASC)
            ->setRoute('users_admin_ajax_list')
            ->setPersistentSettings(true);

        return $crudConfig->getOptions();
    }

    protected function getTemplateName(string $action): string
    {
        return sprintf('user_admin/%s.html.twig', $action);
    }

    #[Route(path: '/users/admin/list', name: 'user_admin_list')]
    public function listAction(): Response
    {
        return $this->getCrudResponse();
    }

    #[Route(path: '/users/admin/ajax/list', name: 'users_admin_ajax_list')]
    public function ajaxCrudAction(): Response
    {
        return $this->getAjaxCrudResponse();
    }

    #[Route(path: '/users/admin/create', name: 'user_admin_ajax_create')]
    public function createAction(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('user_admin_ajax_create'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordHash = $userPasswordHasher->hashPassword($user, (string) $user->plainPassword);
            $user->setPassword($passwordHash);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'message.add_success_user');

            return $this->redirectToRoute('user_admin_ajax_edit', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user_admin/modal_form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route(path: '/users/admin/edit/{id}', name: 'user_admin_ajax_edit')]
    public function editAction(EntityManagerInterface $entityManager, Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('user_admin_ajax_edit', [
                'id' => $user->getId(),
            ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordHash = $userPasswordHasher->hashPassword($user, (string) $user->plainPassword);
            $user->setPassword($passwordHash);
            $entityManager->flush();

            return $this->redirectToRoute('user_admin_ajax_edit', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user_admin/modal_form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route(path: '/users/admin/delete/{id}', name: 'user_admin_ajax_delete')]
    public function deleteAction(EntityManagerInterface $entityManager, User $user): Response
    {
        try {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'message.user_confirm_delete');
        } catch (ForeignKeyConstraintViolationException) {
            $this->addFlash('error', 'message.not_delete');
        }

        return $this->redirectToRoute('users_admin_ajax_list');
    }
}
