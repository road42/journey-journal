<?php

namespace road42\JourneyJournal;

// Return a closure that takes a $page object and returns an array
// TODO: check if still needed our might be a page funktion
return function ($page) {

    // Filter images by the "journey-gallery-image" template and sort by "sort" field
    return [
        'journey-gallery' => $page->images()->filterBy("template", 'journey-gallery-image')->sortBy("sort")
    ];

};

?>