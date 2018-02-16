<?php

namespace Playground\CookiejarBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\File;

class DocumentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('file', FileType::class, array(
				'label' 	=> false,
				'required' 	=> true,
				'constraints' => array(
				   new File(),
			   ),
			));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Playground\CookiejarBundle\Entity\Document'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'playground_cookiejarbundle_document';
    }


}
