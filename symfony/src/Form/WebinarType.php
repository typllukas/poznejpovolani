<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Webinar;
use App\Form\PanelistType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class WebinarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('topic')
            ->add('organization')
            ->add('panelists', CollectionType::class, [
                'entry_type' => PanelistType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('time', TimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('banner', FileType::class, [
                'label' => 'Banner (JPG or PNG file)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PNG/JPEG image'
                    ])
                ]
            ])
            ->add('ReminderText', TextareaType::class, [
                'required' => false,
            ])

            ->add('modules', EntityType::class, [
                'class' => Module::class,
                'choice_label' => 'module', // Assuming 'module' is the property to display in the select options
                'multiple' => true, // If you want to allow multiple selections
                'expanded' => true, // If you want to display the options as checkboxes/radio buttons
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Webinar::class,
        ]);
    }
}
