<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\TagDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class TagFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['max' => 100]),
                    ],
                ]
            )
            ->add(
                'url',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Constraints\Length(['max' => 200]),
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'allow_extra_fields' => true,
            'data_class' => TagDTO::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
