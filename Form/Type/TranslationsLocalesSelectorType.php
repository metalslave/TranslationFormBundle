<?php

namespace A2lix\TranslationFormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author David ALLIX
 */
class TranslationsLocalesSelectorType extends AbstractType
{
    private $locales;

    /**
     * @param $locales
     */
    public function __construct($locales)
    {
        $this->locales = $locales;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => array_combine($this->locales, $this->locales),
            'expanded' => true,
            'multiple' => true,
            'attr' => [
                'class' => 'a2lix_translationsLocalesSelector',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'a2lix_translationsLocalesSelector';
    }
}
