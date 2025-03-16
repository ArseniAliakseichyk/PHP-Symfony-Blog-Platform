<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['is_admin']) {
        $builder->add('roles', ChoiceType::class, [
            'choices' => [
                'Admin' => 'ROLE_ADMIN',
                'User' => 'ROLE_USER',
            ],
            'multiple' => true,
            'expanded' => true,
            'data' => ['ROLE_USER'],
            'constraints' => [
                new NotBlank([
                    'message' => 'You must select at least one role',
                ]),
            ],
        ]);
    }
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'attr' => ['placeholder' => 'Enter your username'],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 20]),
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
    'constraints' => [
        new NotBlank(),
        new Length([
            'min' => 6,
            'max' => 50,
        ]),
    ],
            'mapped' => false,
            'type' => PasswordType::class,
            'invalid_message' => 'Passwords must match',
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => User::class,
        'csrf_protection' => true,
        'csrf_field_name' => '_token',
        'csrf_token_id'   => 'registration',
        'is_admin' => false,
    ]);
}

}