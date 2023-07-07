<?php

namespace Mtt\BlogBundle\Form;

use Mtt\BlogBundle\DTO\CommentDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

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
                TextareaType::class,
                [
                    'constraints' => [
                        new Constraints\NotBlank(),
                        new Constraints\Length(['max' => 1 << 17]),
                    ],
                ]
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
                'topicId',
                IntegerType::class
            )
            ->add(
                'parentId',
                IntegerType::class,
                [
                    'required' => false,
                ]
            )
        ;

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();

                if (isset($data['user'])) {
                    $event->getForm()->add(
                        'user',
                        CommentUserFormType::class
                    );
                } elseif (isset($data['commentator'])) {
                    $event->getForm()->add(
                        'commentator',
                        CommentatorFormType::class
                    );
                }
            }
        );
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
