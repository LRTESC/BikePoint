<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use App\Form\CourseType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Ecommit\CrudBundle\Controller\AbstractCrudController;
use Ecommit\CrudBundle\Crud\Crud;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class CourseController extends AbstractCrudController
{
    protected function getCrudOptions(): array
    {
        $em = $this->container->get('doctrine')->getManager();
        $queryBuilder = $em->getRepository(Course::class)->createQueryBuilder('c')
            ->select('c');

        $crudConfig = $this->createCrudConfig('courses_list_ajax_create');
        $crudConfig->addColumn(['id' => 'id', 'alias' => 'c.id', 'label' => 'label.id'])
            ->addColumn(['id' => 'title', 'alias' => 'c.title', 'label' => 'label.title'])
            ->addColumn(['id' => 'description', 'alias' => 'c.description', 'label' => 'label.description'])
            ->addColumn(['id' => 'zipCode', 'alias' => 'c.zipCode', 'label' => 'label.zipCode'])
            ->addColumn(['id' => 'city', 'alias' => 'c.city', 'label' => 'label.city'])
            ->addColumn(['id' => 'level', 'alias' => 'c.level', 'label' => 'label.level'])
            ->addColumn(['id' => 'distance', 'alias' => 'c.distance', 'label' => 'label.distance'])
            ->addColumn(['id' => 'positiveAscent', 'alias' => 'c.positiveAscent', 'label' => 'label.positiveAscent'])
            ->addColumn(['id' => 'maximumAltitude', 'alias' => 'c.maximumAltitude', 'label' => 'label.maximumAltitude'])
            ->addColumn(['id' => 'negativeGradient', 'alias' => 'c.negativeGradient', 'label' => 'label.negativeGradient'])
            ->addColumn(['id' => 'minimumAltitude', 'alias' => 'c.minimumAltitude', 'label' => 'label.minimumAltitude'])
            ->setQueryBuilder($queryBuilder)
            ->setMaxPerPage([2, 5, 10], 5)
            ->setDefaultSort('id', Crud::ASC)
            ->setRoute('courses_ajax_list')
            ->setPersistentSettings(true);

        return $crudConfig->getOptions();
    }

    protected function getTemplateName(string $action): string
    {
        return sprintf('courses/%s.html.twig', $action);
    }

    #[Route(path: '/courses', name: 'courses')]
    public function listAction(): Response
    {
        return $this->getCrudResponse();
    }

    #[Route(path: '/courses/ajax/list', name: 'courses_ajax_list')]
    public function ajaxCrudAction(): Response
    {
        return $this->getAjaxCrudResponse();
    }

    #[Route('/courses/add', name: 'courses_add')]
    public function addCourse(#[CurrentUser] User $currentUser, Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = new Course();
        $course->setAuthor($currentUser);
        $form = $this->createForm(CourseType::class, $course);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($course);
            $entityManager->flush();

            $this->addFlash('success', 'message.course_successfully_added');

            return $this->redirectToRoute('courses_add');
        }

        return $this->render('courses/form.html.twig', [
            'form' => $form->createView(),
            'course' => $course,
        ]);
    }

    #[Route(path: '/courses/edit/{id}', name: 'courses_edit')]
    public function editAction(EntityManagerInterface $entityManager, Request $request, Course $course): response
    {
        $form = $this->createForm(CourseType::class, $course);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'message.course_successfuly_modified');

            return $this->redirectToRoute('courses', [
                'id' => $course->getId(),
            ]);
        }

        return $this->render('courses/form.html.twig', [
            'form' => $form->createView(),
            'course' => $course,
        ]);
    }

    #[Route(path: '/courses/delete/{id}', name: 'courses_ajax_delete')]
    public function deleteAction(EntityManagerInterface $entityManager, Course $course): response
    {
        try {
            $entityManager->remove($course);
            $entityManager->flush();
            $this->addFlash('success', 'message.the_course_has_been_cancelled');
        } catch (ForeignKeyConstraintViolationException) {
            $this->addFlash('error', 'message.not_delete');
        }

        return $this->redirectToRoute('courses_ajax_list');
    }
}
