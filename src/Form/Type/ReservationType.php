<?php


namespace App\Form\Type;


use App\Entity\Room;
use App\Entity\User;
use App\Repository\RoomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $rooms = $options['rooms'];

        $builder
            ->add('date_from', DateType::class)
            ->add('date_to', DateType::class)
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['rooms']);
    }
}