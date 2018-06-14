<?php

namespace MainBundle\Form;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserPersonalDataType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permissions = array(
            'USER'        => 'ROLE_USER',
            'ADMIN'     => 'ROLE_ADMIN',
        );

        $builder->add('email',
                EmailType::class, ['label' => 'create_users.email'])
            ->add('username',
            TextType::class, ['label' => 'create_users.userName'])
            ->add('password',
                PasswordType::class, ['label' => 'create_users.password'])
            ->add(
                'roles',
                ChoiceType::class,
                array(
                    'choices' => $permissions,
                    'multiple' => false,
                    'label' => 'create_users.roles'
                )
            )
            ->add(
                'submit',
                SubmitType::class,
                ['label' => 'global.submit']
            );

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($roles) {
                    return $roles[0];
                },
                function ($role) {
                    return [$role];
                }
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }


}
