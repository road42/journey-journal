<?php


return function ($kirby) {
    if (kirby()->user() && kirby()->user()->isAdmin())
    {
        return [
            'label' => t('journey-journal.config.label'),
            'icon'  => 'car',
            'menu'  => true,
            'link'  => 'journey-journal-config',
            'views' => [
                [
                    'pattern' => 'journey-journal-config',
                    'action'  => function () {
                        return [
                            'component' => 'journey-journal-place-icons',
                            'props' => [
                                'headline' => t('journey-journal.config.headline'),
                            ]
                        ];
                    }
                ]
            ]
        ];
    }

    return [];
}
?>