<?php

namespace App\Form;

use App\Form\DTO\ArticleTranslationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleTranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $isRequired = $builder->getPropertyPath()->getElement(0) === 'en';
        $builder
            ->add('title', TextType::class, [
                'required' => $isRequired
            ])
            ->add('body', TextareaType::class, [
                'required' => $isRequired
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleTranslationDTO::class,
        ]);
    }
}
