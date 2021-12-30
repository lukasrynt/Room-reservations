<?php

namespace App\Form\Type;

use App\Entity\Group;
use App\Entity\GroupManager;
use App\Entity\Room;
use App\Entity\User;
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
                # TODO change GroupManager to User so that anyone can become GroupManager - somehow cast User to GroupManager in GroupController
                "class" => GroupManager::class,
                'label' => 'Group Manager :',
                'placeholder' => 'Choose an option',
                'required' => false,
            ])
            ->add('rooms', EntityType::class, [
                # TODO selecting rooms doesn't work
                "class" => Room::class,
                'label' => 'Room :',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
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