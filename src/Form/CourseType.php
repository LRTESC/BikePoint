<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Course;
use App\Entity\Level;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'label.title',
            ])
            ->add('description', TextType::class, [
                'label' => 'label.description',
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'label.zipCode',
            ])
            ->add('city', TextType::class, [
                'label' => 'label.city',
            ])
            ->add('level', EntityType::class, [
                'label' => 'label.level',
                'class' => Level::class,
            ])
            ->add('distance', TextType::class, [
                'label' => 'label.distance',
            ])
            ->add('positiveAscent', TextType::class, [
                'label' => 'label.positiveAscent',
            ])
            ->add('maximumAltitude', TextType::class, [
                'label' => 'label.maximumAltitude',
            ])
            ->add('negativeGradient', TextType::class, [
                'label' => 'label.negativeGradient',
            ])
            ->add('minimumAltitude', TextType::class, [
                'label' => 'label.minimumAltitude',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
            'validation_groups' => ['Default', 'EditUser'],
        ]);
    }
}
