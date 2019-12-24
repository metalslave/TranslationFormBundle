Updated for symfony 4.
- Use `GedmoTranslationsType::class`

Example for Sonata Admin.
```
    ->add('translations', GedmoTranslationsType::class, [
        'translatable_class' => $this->getClass(),
        'fields' => [
            'title' => [
                'label' => 'Назва',
                'locale_options' => ['uk' => ['required' => true]],
            ],
            'text' => [
                'label' => 'Текст',
                'locale_options' => ['uk' => ['required' => true]],
            ],
        ],
    ])
```