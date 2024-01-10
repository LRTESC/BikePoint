<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Blank;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'label.firstname',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'label.lastname',
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'label.birth_date',
                'widget' => 'single_text',
            ])
            ->add('city', TextType::class, [
                'label' => 'label.city',
                'mapped' => false,
                'constraints' => [new Blank()],
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'label' => 'label.email',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'invalid_message' => 'message.repeat_password_not_equal',
                'first_options' => [
                    'label' => 'label.plain_password',
                ],
                'second_options' => [
                    'label' => 'label.plain_password_confirm',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['Default', 'EditUser'],
        ]);
    }
}
