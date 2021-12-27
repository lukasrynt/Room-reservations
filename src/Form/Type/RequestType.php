<?php


namespace App\Form\Type;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_from', DateType::class, [
                'input' => 'string'
            ])
            ->add('date_to', DateType::class, [
                'input' => 'string'
            ])
            ->add('attendees', EntityType::class, [
                "class" => User::class,
                'multiple'=> true,
            ]);
    }
}