<?php

namespace road42\JourneyJournal;

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\F as F;

@include_once __DIR__ . '/vendor/autoload.php';

F::loadClasses([
	'road42\\JourneyJournal\\JourneyJournalDayPage' => 'models/JourneyJournalDayPage.php',
    'road42\\JourneyJournal\\JourneyJournalJourneyPage' => 'models/JourneyJournalJourneyPage.php'
], __DIR__);


Kirby::plugin('road42/journey-journal', [
	'blueprints' => [
        'files/journey-journal-image' => __DIR__ . '/blueprints/files/journey-journal-image.yml',
        'files/journey-cover' => __DIR__ . '/blueprints/files/journey-cover.yml',
        'files/journey-gallery-image' => __DIR__ . '/blueprints/files/journey-gallery-image.yml',
        'files/journey-gps-file' => __DIR__ . '/blueprints/files/journey-gps-file.yml',
        'pages/journey-journal-journeys' => __DIR__ . '/blueprints/pages/journey-journal-journeys.yml',
        'pages/journey-journal-journey' => __DIR__ . '/blueprints/pages/journey-journal-journey.yml',
        'pages/journey-journal-journeyday' => __DIR__ . '/blueprints/pages/journey-journal-journeyday.yml',
        'sections/journey-list' => __DIR__ . '/blueprints/sections/journey-list.yml',
        'sections/journey-day-list' => __DIR__ . '/blueprints/sections/journey-day-list.yml',
        'users/journey-journal-visitors' => __DIR__ . '/blueprints/users/journey-journalvisitors.yml'
    ],
    'controllers' => [
        'journey' => require 'controllers/journey.php'
    ],
    'pageModels' => [
        'journey-journal-journey' => JourneyJournalJourneyPage::class,
        'journey-journal-journeyday' => JourneyJournalDayPage::class
    ]

]);
