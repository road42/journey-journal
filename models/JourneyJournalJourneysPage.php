<?php

namespace road42\JourneyJournal;

use Kirby\Cms\Page;

// JourneyJournalJourneysPage extends the Kirby Page class to provide custom methods for journeys-related functionality.
class JourneyJournalJourneysPage extends Page
{
    /**
     * Returns all published journeys that are children of this page.
     *
     * @return \Kirby\Cms\Pages
     */
    public function publishedJourneys()
    {
        return $this->children()
            ->listed()
            ->filterBy('template', 'journey-journal-journey');
    }
}

?>