<?php

namespace Mtt\BlogBundle\Form;

use Mtt\BlogBundle\DTO\CommentDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'text',
                TextareaType::class
            )
            ->add(
                'userAgent',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'ipAddress',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'commentator',
                CommentatorFormType::class
            )
            ->add(
                'topicId',
                IntegerType::class,
                [
                ]
            )
            ->add(
                'parentId',
                IntegerType::class,
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
            'csrf_protection' => false,
            'data_class' => CommentDTO::class,
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
