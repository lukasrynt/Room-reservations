<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->setMethod('GET')
            ->setAction('/groups/search')
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'Group name',
                'attr' => ['placeholder' => 'Group A']
            ])->add('search', SubmitType::class, [
                'attr' => ['class' => 'button-base button-main'],
                'label' => 'Search'
            ]);
    }
}