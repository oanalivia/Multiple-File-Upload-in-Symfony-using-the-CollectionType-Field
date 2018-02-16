<?php

namespace Playground\CookiejarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class FolderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('name', TextType::class, array(
				'required'	=> true,
				'label'		=> 'folder_name'
			));

		$builder
			->add('documents', CollectionType::class, array(
				'entry_type'   		=> DocumentType::class,
				'prototype'			=> true,
				'allow_add'			=> true,
				'allow_delete'		=> true,
				'by_reference' 		=> false,
				'required'			=> false,
				'label'				=> false,
			));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Playground\CookiejarBundle\Entity\Folder'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'playground_cookiejarbundle_folder';
    }
}
