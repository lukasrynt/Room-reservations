<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RoomSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->setMethod('GET')
            ->setAction('/rooms/search')
            ->add('private', ChoiceType::class, [
                'required' => false,
                'label' => 'Usability',
                'choices' => [
                        'public' => false,
                        'private' => true
                    ]
            ])->add('name', TextType::class, [
                'required' => false,
                'label' => 'Classroom name',
                'attr' => ['placeholder' => 'Room 323']
            ])->add('search', SubmitType::class, [
                'attr' => ['class' => 'button-base button-main'],
                'label' => 'Search'
            ]);
    }
}