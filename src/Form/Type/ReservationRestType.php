<?php


namespace App\Form\Type;


use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationRestType extends AbstractType
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
            ->add('user', EntityType::class, [
                "class" => User::class
            ])
            ->add('room', EntityType::class, [
                "class" => Room::class
            ])
            ->add('attendees', EntityType::class, [
                "class" => User::class,
                'multiple'=> true,
            ]);
    }
}