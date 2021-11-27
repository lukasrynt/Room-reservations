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
            ->add('first_name', TextType::class, [
                'required' => false, 'label' => 'Jméno']
            )->add('last_name', TextType::class, [
                'required' => false, 'label' => 'Příjmení']
            )->add('email', TextType::class, [
                'required' => false]
            )->add('phone_number', TextType::class, [
                'required' => false, 'label' => 'Telefonní číslo']
            )->add('search', SubmitType::class, [
                'attr' => ['class' => 'primary-button'],
                'label' => 'Hledat'
            ]);
    }
}