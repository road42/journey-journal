<?php

namespace road42\JourneyJournal;

use Kirby\Cms\Page;

// JourneyJournalJourneyPage extends the Kirby Page class to provide custom methods for journey-related functionality.
class JourneyJournalJourneyPage extends Page
{
    /**
     * Returns all days in the journey as an array.
     * If $onlyUnused is true, only days without a child page with the same date are returned.
     */
    public function allDaysInJourney(bool $onlyUnused = false): array
    {
        $startDate = $this->startDate()->toDate();
        $endDate = $this->endDate()->toDate();

        if (!$startDate || !$endDate || $startDate > $endDate) {
            return [];
        }

        $dates = [];
        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            $formattedDate = date('Y-m-d', $currentDate);

            if (!$onlyUnused || $this->childrenAndDrafts()->filterBy('date', $formattedDate)->isEmpty()) {
                $dates[$formattedDate] = $formattedDate;
            }

            $currentDate = strtotime('+1 day', $currentDate);
        }

        return $dates;
    }

    /**
     * Returns only unused days in the journey (no child page with the same date).
     */
    public function unusedDaysInJourney(): array
    {
        return $this->allDaysInJourney(true);
    }

    /**
     * Returns the next date in the journey as a string (Y-m-d).
     * If there are no dates, returns an empty string.
     *
     * TODO: check if this is needed, as it might be redundant with the startDate() method.
     *
     * @return string The next date in the journey or an empty string.
     */
    public function nextDateInJourney(): string{
        $dates = $this->allDaysInJourney();
        return array_key_first($dates) ?: '';
    }

    /**
     * Returns the cover image associated with the journey page.
     *
     * @return image The cover image object or null if not set.
     */
    public function coverImage()
    {
        return $this->images()->filterBy('template', 'journey-cover')->first();
    }

    /**
     * Checks if the given user has permission to access or modify the journey page.
     *
     * A user has permission if they are an admin or if they are included in the list of users
     * associated with this journey page.
     *
     * @param User $user The user to check permissions for.
     * @return bool True if the user has permission, false otherwise.
     */
    public function userHasPermission($user)
    {
        return $user->isAdmin() || $this->users()->toUsers()->has($user);
    }
}

?>