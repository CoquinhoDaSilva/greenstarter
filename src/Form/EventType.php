<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class, [
                'label'=>'Titre'
            ])
            ->add('date', DateTimeType::class, [
                'label'=>'Date de l\'event'
            ])
            ->add('resume', TextType::class, [
                'label'=>'Description'
            ])
            ->add('content', TextareaType::class, [
                'label'=>'Texte'
            ])
            ->add('pic', FileType::class, [
                'label'=>'image'
            ])
            ->add('picalt', TextType::class, [
                'label'=>'Description de l\'image'
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
