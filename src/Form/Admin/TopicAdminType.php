<?php

namespace App\Form\Admin;

use App\Entity\Profile;
use App\Entity\Topic;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopicAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product')
            ->add('project')
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
            ->add('title')
            ->add('description', TextareaType::class)
            ->add('branch', TextType::class)
            ->add('profile')
            ->add('closedAt', DateType::class, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
