<?php

namespace App\Form;

use App\Entity\Ter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class TerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomSujet',TextType::class,[
                'attr'=>[
                    'class'=> 'form-contol',
                    'minlength'=>'2',
                    'maxlength'=>'50'
                ],
                'label'=>'Non du sujet :',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Length([
                        'min' => 5,
                        'max' => 200,
                        'minMessage' => 'Le champ doit avoir au moins  5  caractères',
                        'maxMessage' => 'Le champ ne peut pas dépasser  200  caractères',

                    ])
                ]
            ]
        )
            ->add('Description',TextareaType::class
                ,[
                    'attr'=>[
                        'style' => 'height: 150px',
                        'class'=> 'form-contol',
                        'minlength'=>'2',
                        'maxlength'=>'50'
                    ],
                    'label'=>'description : ',
                    'label_attr'=>[
                        'class'=>'form-label mt-4'
                    ],
                    'constraints'=>[
                        new Length([
                            'min' => 10,
                            'max' => 5000,
                            'minMessage' => 'Le champ doit avoir au moins  5  caractères',
                            'maxMessage' => 'Le champ ne peut pas dépasser  5000  caractères',

                    ])
                        ]
                    ]

            );

    }





        public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ter::class,
        ]);
    }
}
