<?php

namespace App\Form\Admin;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferEditAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            //TODO: get currencies from data storage
            ->add('currency', CurrencyType::class, ['preferred_choices'=>['UAH', 'EUR', 'USD']])
            ->add('amount', NumberType::class)
            ->add('active', CheckboxType::class)
            ->add('save', SubmitType::class, [
                'label' => 'form_submit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
