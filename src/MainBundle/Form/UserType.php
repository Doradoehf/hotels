<?php

namespace MainBundle\Form;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permissions = array(
            'ROLE_USER'        => 'ROLE_USER',
            'ROLE_ADMIN'     => 'ROLE_ADMIN',
        );

        $builder->add(
                'roles',
                ChoiceType::class,
                array(
                    'choices' => $permissions,
                    'multiple' => false,
                    'label' => 'create_users.roles'
                )
            )
            ->add(
                'save',
                SubmitType::class,
                ['label' => 'global.save']
            );

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($roles) {
                    return $roles[0];
                },
                function ($role) {
                    return [$role];
                }
            ))
        ;
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
