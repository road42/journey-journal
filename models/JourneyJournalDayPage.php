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
    public function dayRoutes(): array
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
    * Returns the geometric middle point (min + (max minus min) / 2) of the whole route
    * (dayRoutes) and all location coordinates, so that the journey map is automatically centered around this point.
    * Gets the smallest and the biggest latitude and longitude from all parsed routes and
    * calculates the middle point.
    *
    * It also returns the range of the route (min/max latitude and longitude) for zooming
    *
    * @return array|null [float, float, float] or null if no points exist
    */
    public function journeyDayMiddlePointCoordinates(): ?array
    {
        $routes = $this->dayRoutes();
        $allPoints = [];

        // Collect all route points
        foreach ($routes as $route) {
            foreach ($route as $point) {
                $allPoints[] = [
                    floatval($point[0]),
                    floatval($point[1])
                ];
            }
        }

        // Collect all location points from the page's locations field (assuming structure field with lat/lng)
        if ($this->locations()->isNotEmpty()) {
            foreach ($this->locations()->toStructure() as $location) {
                $coordinates = $location->location()->value();
                $lat = $coordinates["lat"];
                $lng = $coordinates["lon"];
                if ($lat !== null && $lng !== null && $lat !== '' && $lng !== '') {
                    $allPoints[] = [
                        floatval($lat),
                        floatval($lng)
                    ];
                }
            }
        }

        if (empty($allPoints)) {
            return null;
        }

        $minLat = $maxLat = $allPoints[0][0];
        $minLng = $maxLng = $allPoints[0][1];

        foreach ($allPoints as $point) {
            $lat = $point[0];
            $lng = $point[1];

            if ($lat < $minLat) $minLat = $lat;
            if ($lat > $maxLat) $maxLat = $lat;
            if ($lng < $minLng) $minLng = $lng;
            if ($lng > $maxLng) $maxLng = $lng;
        }

        return [
            $minLat + ($maxLat - $minLat) / 2,
            $minLng + ($maxLng - $minLng) / 2,
            max($maxLat - $minLat, $maxLng - $minLng)
        ];
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