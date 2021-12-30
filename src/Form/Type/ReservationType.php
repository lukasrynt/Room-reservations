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

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $rooms = $options['rooms'];

        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'input' => 'string'
            ])
            ->add('time_from', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'string'
            ])
            ->add('time_to', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'string'
            ])
            ->add('user', EntityType::class, [
                "class" => User::class
            ])
            ->add('room', EntityType::class, [
                "class" => Room::class,
                "query_builder" => function (RoomRepository $er) use ($rooms){
                    return $er->createQueryBuilder('room')
                        ->andWhere("room.id IN(:rooms)")
                        ->setParameter('rooms', $rooms);
                },
            ])
            ->add('attendees', EntityType::class, [
                "class" => User::class,
                'multiple'=> true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['rooms']);
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}