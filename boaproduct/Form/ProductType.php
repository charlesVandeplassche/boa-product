<?php

namespace App\Boa\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Baert\AppBundle\Entity\Product;
use App\Baert\AppBundle\Entity\Product\Translation;
use App\Boa\AdminBundle\Form\TranslatableType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', NumberType::class)
            ->add('discount', NumberType::class, array(
                'required' => false
            ))

            ->add('name', TranslatableType::class, [
                'personal_translation' => Translation::class,
                'property_path'        => 'translations',
                'field'                => 'name',
            ])
            ->add('description', TranslatableType::class, [
                'personal_translation' => Translation::class,
                'property_path'        => 'translations',
                'field'                => 'description',
                'attr'                 => ['rows' => 5],
                'widget'               => TextareaType::class
            ])
            ->add('slug', TranslatableType::class, array(
                'field'                => 'slug',
                'personal_translation' => Translation::class,
                'property_path'        => 'translations',
                'widget'               =>  TextType::class,
            ))
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }
}
