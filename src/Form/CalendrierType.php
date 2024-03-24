<?php

namespace App\Form;

use  App\Entity\Calendrier;
use App\Entity\Classe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ClasseRepository;
use Symfony\Component\Validator\Constraints\Range;

class CalendrierType extends AbstractType
{
    private ClasseRepository $classRepository;

    public function __construct(ClasseRepository $classeRepository)
    {
        $this->classRepository = $classeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dump($options);
        //dd($this->classRepository->getAllClasses());
        $builder
            ->add('titre')
            ->add('debut', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('fin', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['style' => 'height: 150px'],
            ])
            ->add('classes', EntityType::class, [
                'class' => Classe::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('all_day')
            ->add('couleur', ColorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
            'empty_data'
        ]);
    }
}
