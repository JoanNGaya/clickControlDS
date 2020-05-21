<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isbn')
            ->add('title')
            ->add('subtitle')
            ->add('author')
            ->add('published', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('publisher')
            ->add('pages')
            ->add('description')
            ->add('website')
            ->add('category')
            ->add('photo_urls', FileType::class, [
                'mapped' => false,
                'label' => 'Add images',
                'multiple' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
