<?php

namespace App\Form;

use App\Model\MeetupRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('plannedAt', DateTimeType::class, [
                'widget' => 'single_text',
//                'html5' => false,
//                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('duration', ChoiceType::class, ['choices' => [
                '30 minutes' => 1,
                '1 hour' => 2,
                '1.5 hours' => 3,
                '2 hours' => 4,
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MeetupRequest::class,
        ]);
    }
}
