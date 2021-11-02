<?php


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\FormBuilderInterface;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('capacity', IntegerType::class)
            ->add('name', TextType::class)
            ->add('floor', IntegerType::class)
            ->add('opened_from', DateTimeType::class)
            ->add('opened_to', DateTimeType::class);
    }
}