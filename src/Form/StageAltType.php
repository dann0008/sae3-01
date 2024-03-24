<?php

namespace App\Form;

use App\Entity\StageAlt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class StageAltType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr'=>[
                    'class'=> 'form-contol',
                    'minlength'=>'2',
                    'maxlength'=>'50'
                ],
                'label'=>'Non du stage ou TER :',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Length([
                        'min' => 5,
                        'max' => 50,
                        'minMessage' => 'Le champ doit avoir au moins  5  caractères',
                        'maxMessage' => 'Le champ ne peut pas dépasser  50  caractères',

                    ])
                ]
            ])
            ->add('description', TextAreaType::class, [
                'attr'=>[
                    'style' => 'height: 150px',
                    'class'=> 'form-contol',
                    'minlength'=>'2',
                    'maxlength'=>'2000'
                ],
                'label'=>'description : ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Length([
                        'min' => 10,
                        'max' => 2000,
                        'minMessage' => 'Le champ doit avoir au moins  5  caractères',
                        'maxMessage' => 'Le champ ne peut pas dépasser  2000  caractères',

                    ])
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Alternance' => 'Alternance',
                    'Stage' => 'Stage',
                ]
                ,
                'placeholder'=>'Alternance ou Stage',
            ])

            ->add('dateDebut', DateTimeType::class,[
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateTimeType::class,[
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StageAlt::class,
        ]);
    }
}
