<?php

namespace A2lix\TranslationFormBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Gedmo\Translatable\TranslatableListener;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ControllerListener
{
    protected $annotationReader;
    protected $translatableListener;

    public function __construct(Reader $annotationReader, TranslatableListener $translatableListener)
    {
        $this->annotationReader = $annotationReader;
        $this->translatableListener = $translatableListener;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        list($object, $method) = $controller;

        $className = ClassUtils::getClass($object);
        $reflectionClass = new \ReflectionClass($className);
        $reflectionMethod = $reflectionClass->getMethod($method);

        if ($this->annotationReader->getMethodAnnotation($reflectionMethod, 'A2lix\TranslationFormBundle\Annotation\GedmoTranslation')) {
            $this->translatableListener->setTranslatableLocale($this->translatableListener->getDefaultLocale());
        }
    }
}