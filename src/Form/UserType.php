<?php

// src/Form/UserType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Full Name',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrator' => 'ROLE_ADMIN',
                    'Report Admin' => 'ROLE_REPORT_ADMIN',
                    'Group Administrator' => 'ROLE_GROUP_ADMIN',
                    'Standard User' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'New Password',
                'required' => false,
                'mapped' => false,
                'help' => 'Leave blank to keep current password',
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
            ]);
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
