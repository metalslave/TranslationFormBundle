<?php

namespace A2lix\TranslationFormBundle\Form\Type;

use A2lix\TranslationFormBundle\Form\DataMapper\IndexByTranslationMapper;
use A2lix\TranslationFormBundle\Form\EventListener\DefaultTranslationsListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Regroup by locales, all translations fields.
 *
 * @author David ALLIX
 */
class TranslationsType extends AbstractType
{
    private $translationsListener;
    private $locales;
    private $required;

    /**
     * @param DefaultTranslationsListener $translationsListener
     * @param $locales
     * @param $required
     */
    public function __construct(DefaultTranslationsListener $translationsListener, $locales, $required)
    {
        $this->translationsListener = $translationsListener;
        $this->locales = $locales;
        $this->required = $required;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper(new IndexByTranslationMapper());
        $builder->addEventSubscriber($this->translationsListener);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'by_reference' => false,
            'required' => $this->required,
            'locales' => $this->locales,
            'fields' => [],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'a2lix_translations';
    }
}
