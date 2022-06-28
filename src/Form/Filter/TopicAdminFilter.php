<?php

namespace App\Form\Filter;

use App\Model\TopicFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopicAdminFilter extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, ['choices' => [
                '' => null,
                'filter.task' => 'task',
                'filter.filters' => 'filters',
                'filter.external' => 'external',
                'filter.controller' => 'controller',
                'filter.test' => 'test',
                'filter.dashboard' => 'dashboard',
                'filter.security' => 'security',
                'filter.admin' => 'admin',
                'filter.logger' => 'logger',
                'filter.migration' => 'migration',
            ]])
            ->add('apply', SubmitType::class)
            ->add('clear', SubmitType::class)
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TopicFilter::class,
        ]);
    }
}
