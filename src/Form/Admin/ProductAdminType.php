<?php

namespace App\Form\Admin;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('parent', EntityType::class, [
                'class' => Product::class,
                'required' => false,
                'choice_label' => function (?Product $category) {
                    return $category ? str_repeat('-', $category->getLevel()). $category->getTitle() : '';
                },
//                'query_builder' => function (ProductRepository $repository) {
//                    return $repository->createQueryBuilder('c')
//                        ->orderBy('c.level', 'ASC');
//                },
            ])
            ->add('description', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
