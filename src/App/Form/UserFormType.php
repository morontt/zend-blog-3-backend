<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\UserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class UserFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['max' => 128]),
                    ],
                ]
            )
            ->add(
                'displayName',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Constraints\Length(['max' => 64]),
                    ],
                ]
            )
            ->add(
                'email',
                TextType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Email(),
                        new Constraints\Length(['max' => 64]),
                    ],
                ]
            )
            ->add(
                'isMale',
                CheckboxType::class
            )
            ->add(
                'role',
                ChoiceType::class,
                [
                    'choices' => [
                        'admin',
                        'guest',
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
            'data_class' => UserDTO::class,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
