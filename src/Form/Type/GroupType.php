<?php

namespace App\Form\Type;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('name', TextType::class, [
                'label' => 'Group name :',
            ])
            ->add('groupManager', EntityType::class, [
                "class" => User::class,
                'label' => 'Group Manager :',
                'placeholder' => 'Choose an option',
                'required' => false,
            ])
            ->add('parent', EntityType::class, [
                "class" => Group::class,
                'choice_label' => 'name',
                'label' => 'Parent group :',
                'placeholder' => 'Choose an option',
                'required' => false,
            ])
            ->add('edit', SubmitType::class, [
                'attr' => ['class' => 'button-base button-success'],
                'label' => 'Save'
            ]);
    }
}