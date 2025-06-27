<?php

namespace road42\JourneyJournal;

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\F as F;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('road42/journey-journal', [
	'blueprints' => [
        'files/journey-journal-image' => __DIR__ . '/blueprints/files/journey-journal-image.yml',
        'files/journey-cover' => __DIR__ . '/blueprints/files/journey-cover.yml',
        'files/journey-gallery-image' => __DIR__ . '/blueprints/files/journey-gallery-image.yml',
        'files/journey-gps-file' => __DIR__ . '/blueprints/files/journey-gps-file.yml'
    ]
]);
