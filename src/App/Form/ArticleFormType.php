<?php

namespace App\Form;

use App\DTO\ArticleDTO;
use App\Validator\Constraints\DateTimeString;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class ArticleFormType extends AbstractType
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
                TextareaType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['max' => 65535]),
                    ],
                ]
            )
            ->add(
                'categoryId',
                IntegerType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                    ],
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['max' => 128]),
                    ],
                ]
            )
            ->add(
                'url',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Constraints\Length(['max' => 255]),
                    ],
                ]
            )
            ->add(
                'hidden',
                CheckboxType::class
            )
            ->add(
                'disableComments',
                CheckboxType::class
            )
            ->add(
                'description',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Constraints\Length(['max' => 255]),
                    ],
                ]
            )
            ->add(
                'tagsString',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'forceCreatedAt',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new DateTimeString(),
                    ],
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
            'allow_extra_fields' => true,
            'data_class' => ArticleDTO::class,
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
