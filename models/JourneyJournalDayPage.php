<?php

namespace road42\JourneyJournal;

use Kirby\Cms\Page;

// Define a custom page model for a journey day, extending Kirby's Page class
class JourneyJournalDayPage extends Page
{
    /**
     * Returns the structure of journey sections from the parent page.
     *
     * @return \Kirby\Cms\Structure
     */
    public function Sections(): \Kirby\Cms\Structure
    {
        return $this->parent()->journeySections()->toStructure();
    }

    /**
     * Returns the title of the selected section for this day.
     *
     * @return string
     */
    public function SectionName(): string
    {
        $selectedId = $this->section()->value();
        $sections = $this->Sections();

        foreach ($sections as $section) {
            if ($section->id() == $selectedId) {
                return $section->title()->value();
            }
        }

        return "";
    }

    // Reads the GPS file, parses each line as a pair of coordinates, and returns an array of routes.
    public function parsedRoutes(): array
    {
        $routes = [];
        $gpsFiles = $this->gpsfile()->toFiles();

        foreach ($gpsFiles as $gpsFile) {
            $route = [];

            // simple csv files
            if ($gpsFile->extension() === 'csv') {
                $content = $gpsFile->read();
                $lines = explode("\n", $content);
                foreach ($lines as $line) {
                    $coordinates = array_map('trim', explode(',', $line));
                    if (count($coordinates) === 2) {
                        $route[] = $coordinates;
                    }
                }
            }

            // Support for GPX (XML) files
            elseif ($gpsFile->extension() === 'gpx') {
                $content = $gpsFile->read();
                $xml = simplexml_load_string($content);
                if ($xml !== false) {
                    // GPX files usually have trk > trkseg > trkpt elements
                    foreach ($xml->trk as $trk) {
                        foreach ($trk->trkseg as $trkseg) {
                            foreach ($trkseg->trkpt as $trkpt) {
                                $lat = (string)$trkpt['lat'];
                                $lon = (string)$trkpt['lon'];
                                if ($lat !== '' && $lon !== '') {
                                    $route[] = [$lat, $lon];
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($route)) {
                $routes[$gpsFile->hash()] = $route;
            }
        }

        return $routes;
    }

    /**
     * Returns an array of dates for the journey, including unused days and the current day's date.
     *
     * @return array
     */
    public function Dates()
    {
        $dates = $this->parent()->unusedDaysInJourney();
        $currentDate = $this->date()->toDate('Y-m-d');
        if ($currentDate) {
            $dates[] = $currentDate;
            sort($dates);
        }
        return $dates;
    }

    /**
     * Returns the cover image associated with the journey page.
     *
     * @return image The cover image object or null if not set.
     */
    public function coverImage()
    {
        return $this->images()->filterBy('template', 'journey-cover')->first();

        //images()->filterBy('template', 'journey-gallery-image')
    }

    /**
     * Returns all gallery images associated with the journey page.
     */
    public function galleryImages()
    {
        return $this->images()->filterBy('template', 'journey-gallery-image')->sortBy('sort', 'asc');
    }

    /**
     * Returns if the user has permission to comment on the page.
     */
    public function userCanComment(): bool
    {
        return $this->parent()->userCanComment();
    }

    /**
     * Return if the user can read comments on the page.
     */
    public function userCanReadComments(): bool
    {
        return $this->parent()->userCanReadComments();
    }
}
