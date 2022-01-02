<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BuildingSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->setMethod('GET')
            ->setAction('/buildings/search')
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Building name',
                'attr' => ['placeholder' => 'Národní technická knihovna']
            ])->add('address', TextType::class, [
                'required' => false,
                'label' => 'Address',
                'attr' => ['placeholder' => 'Technická 2710/6']
            ])->add('search', SubmitType::class, [
                'attr' => ['class' => 'button-base button-main'],
                'label' => 'Search'
            ]);
    }
}