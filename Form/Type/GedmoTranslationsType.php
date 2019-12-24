<?php

namespace A2lix\TranslationFormBundle\Form\Type;

use A2lix\TranslationFormBundle\Form\DataMapper\GedmoTranslationMapper;
use A2lix\TranslationFormBundle\Form\EventListener\GedmoTranslationsListener;
use A2lix\TranslationFormBundle\TranslationForm\GedmoTranslationForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Regroup by locales, all translations fields (gedmo).
 *
 * @author David ALLIX
 */
class GedmoTranslationsType extends AbstractType
{
    private $translationsListener;
    private $translationForm;
    private $locales;
    private $required;

    /**
     * @param GedmoTranslationsListener $translationsListener
     * @param GedmoTranslationForm      $translationForm
     * @param $locales
     * @param $required
     */
    public function __construct(GedmoTranslationsListener $translationsListener, GedmoTranslationForm $translationForm, $locales, $required)
    {
        $this->translationsListener = $translationsListener;
        $this->translationForm = $translationForm;
        $this->locales = $locales;
        $this->required = $required;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Simple way is enough
        if (!$options['inherit_data']) {
            $builder->setDataMapper(new GedmoTranslationMapper());
            $builder->addEventSubscriber($this->translationsListener);
        } else {
            if (!$options['translatable_class']) {
                throw new \Exception("If you want include the default locale with translations locales, you need to fill the 'translatable_class' option");
            }

            $childrenOptions = $this->translationForm->getChildrenOptions($options['translatable_class'], $options);
            foreach ($childrenOptions as $keyLang => $lang) {
                foreach ($lang as $keyItem => $item) {
                    foreach ($item as $field => $value) {
                        if (\in_array($field, ['max_length', 'pattern'], true)) {
                            unset($item[$field]);
                            $item['attr'][$field] = $value;
                            $childrenOptions[$keyLang][$keyItem] = $item;
                        }
                    }
                }
            }
            $defaultLocale = (array) $this->translationForm->getGedmoTranslatableListener()->getDefaultLocale();

            $builder->add('defaultLocale', GedmoTranslationsLocalesType::class, [
                'locales' => $defaultLocale,
                'fields_options' => $childrenOptions,
                'inherit_data' => true,
            ]);

            $builder->add($builder->getName(), GedmoTranslationsLocalesType::class, [
                'locales' => array_diff($options['locales'], $defaultLocale),
                'fields_options' => $childrenOptions,
                'inherit_data' => false,
                'translation_class' => $this->translationForm->getTranslationClass($options['translatable_class']),
            ]);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['simple_way'] = !$options['inherit_data'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $translatableListener = $this->translationForm->getGedmoTranslatableListener();

        $resolver->setDefaults([
            'required' => $this->required,
            'locales' => $this->locales,
            'fields' => [],
            'translatable_class' => null,

            // inherit_data is needed only if there is no persist of default locale and default locale is required to display
            'inherit_data' => function (Options $options) use ($translatableListener) {
                return !$translatableListener->getPersistDefaultLocaleTranslation()
                    && (\in_array($translatableListener->getDefaultLocale(), $options['locales']));
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'a2lix_translations_gedmo';
    }
}
