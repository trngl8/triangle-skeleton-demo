<?php

namespace App\Form;

use App\Entity\Check;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'type.single' => 'single',     // radio
                    'type.multiply' => 'multiply', // checkbox
                    'type.open' => 'open'
                ]
            ])
            ->add('options', CollectionType::class, [
                'label' => false,
                'entry_type' => OptionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class)
            ->add('saveAndAdd', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Check::class,
        ]);
    }
}
