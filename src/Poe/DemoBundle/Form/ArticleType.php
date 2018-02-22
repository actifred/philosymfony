<?php

namespace Poe\DemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',      DateTimeType::class)
            ->add('titre',     TextType::class)
            ->add('auteur',    TextType::class)
            ->add('contenu',   TextareaType::class)
            ->add('featuredimage', ImageType::class)
            ->add('categories', CollectionType::class, array(
                'entry_type'   => CategoryType::class,
                'allow_add'    => true,
                'allow_delete' => true
              ))
            ->add('save',      SubmitType::class);    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Poe\DemoBundle\Entity\Article'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'poe_demobundle_article';
    }


}
