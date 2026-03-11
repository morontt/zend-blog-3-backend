<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 17.05.2025
 * Time: 20:32
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class AvatarFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'commentator_id',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Constraints\NotBlank(),
                    ],
                ]
            )
            ->add(
                'upload',
                FileType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Image([
                            'maxSize' => '512Ki',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                        ]),
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
