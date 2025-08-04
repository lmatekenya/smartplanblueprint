<?php

namespace App\Form;

use App\Entity\LimitsAndDocs\CreditLimit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditLimitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', MoneyType::class, [
                'currency' => 'BWP',
                'label' => 'Credit Amount'
            ])
            ->add('expiryDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Expiry Date'
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Approved' => 'Approved',
                    'Pending' => 'Pending',
                    'Rejected' => 'Rejected',
                    'Expired' => 'Expired'
                ],
                'label' => 'Status'
            ])
            ->add('authorizedBy', TextType::class, [
                'label' => 'Authorized By'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreditLimit::class,
        ]);
    }
}
