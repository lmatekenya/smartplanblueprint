<?php

namespace App\Form;

use App\Entity\LimitsAndDocs\Provider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProviderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Provider Name'
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Payment' => 'payment',
                    'Banking' => 'banking',
                    'Utility' => 'utility',
                    'Other' => 'other'
                ],
                'label' => 'Provider Type'
            ])
            ->add('isEnabled', CheckboxType::class, [
                'label' => 'Is Enabled',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
        ]);
    }
}
