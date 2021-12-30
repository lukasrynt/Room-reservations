<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->setMethod('GET')
            ->setAction('/users/search')
            ->add('firstName', TextType::class, [
                'required' => false,
                'label' => 'First Name',
                'attr' => ['placeholder' => 'John']
                ]
            )->add('lastName', TextType::class, [
                'required' => false,
                'label' => 'Last Name',
                'attr' => ['placeholder' => 'Doe']
                ]
            )->add('email', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'example@xyz.com']
                ]
            )->add('phoneNumber', TextType::class, [
                'required' => false,
                'label' => 'Phone Number',
                'attr' => ['placeholder' => '111222333']
                ]
            )->add('search', SubmitType::class, [
                'attr' => ['class' => 'button-base button-main'],
                'label' => 'Search'
            ]);
    }
}