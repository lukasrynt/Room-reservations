<?php


namespace App\Form\Type;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

class BookRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, [
                'input' => 'string'
            ])
            ->add('time_from', TimeType::class, [
                'input' => 'string'
            ])
            ->add('time_to', TimeType::class, [
                'input' => 'string'
            ])
            ->add('attendees', EntityType::class, [
                "class" => User::class,
                'multiple'=> true,
            ]);
    }
}