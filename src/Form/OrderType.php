<?php

namespace App\Form;

use App\Model\OrderRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\IsTrue;

class OrderType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user){
            if(!$user) {
                $event->getForm()->add('description', TextType::class, [
                    'label' => 'form.label.contact_email',
                ]);
            }

            if($user) {
                $event->getForm()->add('description', TextareaType::class, [
                    'label' => 'form.label.description'
                ]);
            }
        });

        $builder
            ->add('title', HiddenType::class)
            ->add('currency', HiddenType::class)
            ->add('amount', HiddenType::class)
            //->add('description', TextareaType::class)
        ;



        $builder
            ->add('agree', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => 'form.label.agree'
            ])
        ;
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderRequest::class,
        ]);
    }
}
