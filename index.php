<?php

namespace road42\JourneyJournal;

use Kirby\Cms\App as Kirby;
use Kirby\Data\Yaml as Yaml;
use Kirby\Filesystem\F as F;

@include_once __DIR__ . '/vendor/autoload.php';

F::loadClasses([
	'road42\\JourneyJournal\\JourneyJournalDayPage' => 'models/JourneyJournalDayPage.php',
    'road42\\JourneyJournal\\JourneyJournalJourneyPage' => 'models/JourneyJournalJourneyPage.php',
    'road42\\JourneyJournal\\JourneyJournalJourneysPage' => 'models/JourneyJournalJourneysPage.php'
], __DIR__);

Kirby::plugin('road42/journey-journal', [
    'translations' => [
        'en' => require_once __DIR__ . '/languages/en.php',
        'de' => require_once __DIR__ . '/languages/de.php',
    ],
	'blueprints' => [
        'files/journey-journal-image' => __DIR__ . '/blueprints/files/journey-journal-image.yml',
        'files/journey-cover' => __DIR__ . '/blueprints/files/journey-cover.yml',
        'files/journey-gallery-image' => __DIR__ . '/blueprints/files/journey-gallery-image.yml',
        'files/journey-gps-file' => __DIR__ . '/blueprints/files/journey-gps-file.yml',
        'pages/journey-journal-journeys' => __DIR__ . '/blueprints/pages/journey-journal-journeys.yml',
        'pages/journey-journal-journey' => __DIR__ . '/blueprints/pages/journey-journal-journey.yml',
        'pages/journey-journal-day' => __DIR__ . '/blueprints/pages/journey-journal-day.yml',
        'sections/journey-list' => __DIR__ . '/blueprints/sections/journey-list.yml',
        'sections/journey-day-list' => __DIR__ . '/blueprints/sections/journey-day-list.yml',
        'users/journey-journal-editor' => __DIR__ . '/blueprints/users/journey-journal-editor.yml',
        'users/journey-journal-visitor' => __DIR__ . '/blueprints/users/journey-journal-visitor.yml',
    ],
    'controllers' => [
        'journey' => require 'controllers/journey.php'
    ],
    'pageModels' => [
        'journey-journal-journeys' => JourneyJournalJourneysPage::class,
        'journey-journal-journey' => JourneyJournalJourneyPage::class,
        'journey-journal-day' => JourneyJournalDayPage::class
    ],
    'areas' => [
        'journey-journal-config' => require_once __DIR__ . '/areas/journey-journal-config.php'
    ],
    'api' => [
        'routes' => [
            [
                'pattern' => 'journey-journal/place-icons',
                'language' => '*',
                'method'  => 'GET',
                'action'  => function () {
                    $yaml = site()->content()->get('placeIcons')->value();
                    return Yaml::decode($yaml ?? '') ?? [];
                }
            ],
            [
                'pattern' => 'journey-journal/place-icons',
                'method'  => 'POST',
                'language' => '*',
                'action'  => function () {
                    $data = kirby()->request()->data();
                    site()->update([
                        'placeIcons' => Yaml::encode($data)
                    ]);
                    return ['status' => 'ok'];
                }
            ]
        ],
    ],
    'panel' => [
        'plugin' => __DIR__ . '/panel/index.js'
    ],
]);

?>