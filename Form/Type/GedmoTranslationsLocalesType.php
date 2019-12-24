<?php

namespace A2lix\TranslationFormBundle\Form\Type;

use A2lix\TranslationFormBundle\Form\DataMapper\GedmoTranslationMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Translations locales (gedmo).
 *
 * @author David ALLIX
 */
class GedmoTranslationsLocalesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isDefaultTranslation = ('defaultLocale' === $builder->getName());

        // Custom mapper for translations
        if (!$isDefaultTranslation) {
            $builder->setDataMapper(new GedmoTranslationMapper());
        }

        foreach ($options['locales'] as $locale) {
            if (isset($options['fields_options'][$locale])) {
                $builder->add($locale, TranslationsFieldsType::class, [
                    'fields' => $options['fields_options'][$locale],
                    'translation_class' => $options['translation_class'],
                    'inherit_data' => $isDefaultTranslation,
                ]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'locales' => [],
            'fields_options' => [],
            'translation_class' => null,
        ]);
    }

    /**
     * BC for SF < 2.8.
     *
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'a2lix_translationsLocales_gedmo';
    }
}
