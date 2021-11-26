<?php

namespace App\Form\Type;

use App\Entity\Building;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\FormBuilderInterface;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('name', TextType::class, [
                'label' => 'Classroom name :',
            ])
            ->add('building', EntityType::class, [
                "class" => Building::class,
                'label' => 'Building :'
            ])
            ->add('floor', IntegerType::class, [
                'label' => 'Floor :'
            ])
            ->add('capacity', IntegerType::class, [
                    'label' => 'Capacity :'
                ])
            ->add('opened_from', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'From :'
            ])
            ->add('opened_to', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'To :'
            ])->add('edit', SubmitType::class, [
                'attr' => ['class' => 'hidden'],
                'label' => 'Save',
                'disabled' => true
            ])->add('delete', ButtonType::class, [
                'attr' => ['class' => 'hidden'],
                'label' => 'Delete'
            ]);
    }
}

