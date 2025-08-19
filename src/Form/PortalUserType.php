<?php
//
//namespace App\Form;
//
//use App\Entity\Merchant\PortalUserDetails;
//use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
//use Symfony\Component\Form\Extension\Core\Type\EmailType;
//use Symfony\Component\Form\Extension\Core\Type\PasswordType;
//use Symfony\Component\Form\Extension\Core\Type\TextType;
//use Symfony\Component\Form\FormBuilderInterface;
//use Symfony\Component\OptionsResolver\OptionsResolver;
//
//class PortalUserType extends AbstractType
//{
//    public function buildForm(FormBuilderInterface $builder, array $options): void
//    {
//        $builder
//            ->add('name', TextType::class, [
//                'label' => 'Full Name'
//            ])
//            ->add('email', EmailType::class, [
//                'label' => 'Email (Username)'
//            ])
//            ->add('plainPassword', PasswordType::class, [
//                'label' => 'New Password',
//                'required' => false,
//                'mapped' => false,
//                'attr' => [
//                    'placeholder' => 'Leave blank to keep current password'
//                ]
//            ])
//            ->add('isActive', CheckboxType::class, [
//                'label' => 'Active User',
//                'required' => false,
//                'mapped' => false,
//                'data' => $options['data']->isEnabled()
//            ]);
//    }
//
//    public function configureOptions(OptionsResolver $resolver): void
//    {
//        $resolver->setDefaults([
//            'data_class' => PortalUserDetails::class,
//        ]);
//    }
//}


// src/Form/PortalUserType.php
namespace App\Form;

use App\Entity\Merchant\PortalUserDetails;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortalUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Full Name'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email (Username)'
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'New Password',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Leave blank to keep current password'
                ]
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active User',
                'required' => false,
                'mapped' => false,
                'data' => $options['data']->isEnabled()
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
