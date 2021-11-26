<?php


namespace App\Form\Type;


use App\Entity\Room;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('date_from', DateType::class)
            ->add('date_to', DateType::class)
            ->add('valid', DateType::class)
            ->add('room', EntityType::class,[
                "class" => Room::class
            ]);
    }
}