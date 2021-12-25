<?php

namespace App\Form\Type;

use App\Entity\EnumRolesType;
use App\Entity\Roles;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add("first_name", TextType::class, [
                'attr' => ['placeholder' => 'First name']
            ])
            ->add("last_name", TextType::class, [
                'attr' => ['placeholder' => 'Last name']
            ])
            ->add("email", TextType::class, [
                'attr' => ['placeholder' => 'Email']
            ])
            ->add("phone_number", TelType::class, [
                'attr' => ['placeholder' => 'Phone number']
            ])
            ->add("role", ChoiceType::class, [
                'placeholder' => 'Vyberte roli uživatele',
                'choices' => Roles::getAll()
            ])
            ->add("note", TextareaType::class, [
                'attr' => ['placeholder' => 'Note']
            ])
            ->add("username", TextType::class, [
                'attr' => ['placeholder' => 'Username']
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                    ]),
                ],
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}