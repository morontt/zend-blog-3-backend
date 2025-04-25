<?php

namespace App\Form;

use App\DTO\CommentatorDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

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
                TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['max' => 80]),
                    ],
                ]
            )
            ->add(
                'email',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Constraints\Email(),
                        new Constraints\Length(['max' => 80]),
                    ],
                ]
            )
            ->add(
                'website',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Constraints\Length(['max' => 160]),
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
