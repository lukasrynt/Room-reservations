<?php

namespace App\Form\Type;

use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('date_from', DateType::class)
            ->add('date_to', DateType::class)
            ->add('valid', DateType::class)
            ->add('room_id', IntegerType::class)
            ->add('requestor_id', IntegerType::class);
    }
}