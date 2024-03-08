<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Course;
use App\Entity\Level;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'label.title2',
            ])
            ->add('description', TextType::class, [
                'label' => 'label.description2',
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'label.zipCode2',
            ])
            ->add('city', TextType::class, [
                'label' => 'label.city2',
            ])
            ->add('level', EntityType::class, [
                'label' => 'label.level2',
                'class' => Level::class,
            ])
            ->add('distance', TextType::class, [
                'label' => 'label.distance2',
            ])
            ->add('positiveAscent', TextType::class, [
                'label' => 'label.positiveAscent2',
            ])
            ->add('maximumAltitude', TextType::class, [
                'label' => 'label.maximumAltitude2',
            ])
            ->add('negativeGradient', TextType::class, [
                'label' => 'label.negativeGradient2',
            ])
            ->add('minimumAltitude', TextType::class, [
                'label' => 'label.minimumAltitude2',
            ])
            ->add('picture', FileType::class, options: [
                'label' => 'label.pictureCourse',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'message.please_upload_a_valid_image',
                    ]),
                ],
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
