<?php

namespace A2lix\TranslationFormBundle\TranslationForm;

/**
 * @author David ALLIX
 */
interface TranslationFormInterface
{
    /**
     * @param $class
     * @param $options
     *
     * @return mixed
     */
    public function getChildrenOptions($class, $options);

    /**
     * @param $guesser
     * @param $class
     * @param $property
     * @param $options
     *
     * @return mixed
     */
    public function guessMissingChildOptions($guesser, $class, $property, $options);
}
