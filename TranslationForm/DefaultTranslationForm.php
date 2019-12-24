<?php

namespace A2lix\TranslationFormBundle\TranslationForm;

/**
 * @author David ALLIX
 */
class DefaultTranslationForm extends TranslationForm
{
    /**
     * @param type $translationClass
     *
     * @return type
     */
    protected function getTranslatableFields($translationClass)
    {
        $translationClass = \Doctrine\Common\Util\ClassUtils::getRealClass($translationClass);
        $manager = $this->getManagerRegistry()->getManagerForClass($translationClass);
        $metadataClass = $manager->getMetadataFactory()->getMetadataFor($translationClass);

        $fields = [];
        foreach ($metadataClass->fieldMappings as $fieldMapping) {
            if (!\in_array($fieldMapping['fieldName'], ['id', 'locale'])) {
                $fields[] = $fieldMapping['fieldName'];
            }
        }

        return $fields;
    }
}
