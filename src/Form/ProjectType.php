<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'=>'Titre'
            ])
            ->add('amountneeded', TextType::class, [
                'label'=>'Montant visé'
            ])
            ->add('enddate', DateTimeType::class, [
                'label'=>'Date de fin'
            ])
            ->add('resume', TextType::class, [
                'label'=>'Description'
            ])
            ->add('content', TextType::class, [
                'label'=>'Texte'
            ])
            ->add('pic', FileType::class, [
                'label'=>'Image'
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
            'data_class' => Project::class,
        ]);
    }
}
