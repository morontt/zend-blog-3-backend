<?php

namespace Mtt\BlogBundle\Form;

use Mtt\BlogBundle\DTO\CommentatorDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentatorFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class
            )
            ->add(
                'email',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'website',
                TextType::class,
                [
                    'required' => false,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentatorDTO::class,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
