<?php

namespace App\Form\Type;

use App\Entity\Building;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\FormBuilderInterface;

class NameRoomManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('room_manager', EntityType::class, [
                'class' => User::class,
                'label' => 'Room Manager :',
                'placeholder' => 'Choose room manager',
                'required' => false,
            ])->add('edit', SubmitType::class, [
                'attr' => ['class' => 'button-base button-success'],
                'label' => 'Appoint'
            ]);
    }
}

