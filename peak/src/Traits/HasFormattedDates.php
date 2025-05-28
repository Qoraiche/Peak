<?php

namespace Qoraiche\Peak\Traits;

use Carbon\Exceptions\InvalidFormatException;
use Exception;
use Illuminate\Support\Carbon;
use Log;
use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Settings\Admin\WebsiteGeneralSettings;

trait HasFormattedDates
{
    protected array $rawAttributes = [];

    /**
     * Disable formatting for the specified date columns.
     *
     * @param array $columns
     * @return $this
     */
    public function withoutFormattingDates(array $columns = [])
    {
        $this->rawAttributes = $columns;
        return $this;
    }

    /**
     * Automatically apply formatting to created_at, updated_at, and any other date fields.
     */
    public function getCreatedAtAttribute($value)
    {
        if (app()->runningInConsole()) {
            return $this->asDateTime($value);
        }

        return $this->formatDateAttribute($value, $this->shouldUseDateTimeFormat('created_at'));
    }

    /**
     * Format a date attribute based on the model's preference.
     *
     * @param string|null $value
     * @param bool $useDateTimeFormat
     * @return string|null
     */
    protected function formatDateAttribute($value, $useDateTimeFormat = false)
    {
        if (!$value) {
            return null;
        }

        $settings = app(WebsiteGeneralSettings::class);

        $format = $useDateTimeFormat
            ? $settings->getTranslatable('date_time_format')
            : $settings->getTranslatable('date_format');

        if (empty($format) || !$this->isValidDateFormat($format)) {
            return $value;
        }

        try {
            Carbon::setLocale(Helpers::getLocale());
            return Carbon::parse($value)->translatedFormat($format);

        } catch (InvalidFormatException $e) {
            Log::error('error parsing date', [
                'message' => $e->getMessage()
            ]);

            return $value;
        }
    }

    /**
     * Validate if the date format is valid.
     *
     * @param string $format
     * @return bool
     */
    protected function isValidDateFormat($format)
    {
        try {
            Carbon::now()->translatedFormat($format);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Check if a column should use the date-time format.
     */
    protected function shouldUseDateTimeFormat($key)
    {
        return isset($this->dateTimeFormattedDates)
            && is_array($this->dateTimeFormattedDates)
            && in_array($key, $this->dateTimeFormattedDates);
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getUpdatedAtAttribute($value)
    {
        if (app()->runningInConsole()) {
            return $this->asDateTime($value);
        }

        return $this->formatDateAttribute($value, $this->shouldUseDateTimeFormat('updated_at'));
    }

    /**
     * @param $value
     * @return string|null
     */
    public function getPublishedAtAttribute($value)
    {
        return $this->formatDateAttribute($value, $this->shouldUseDateTimeFormat('published_at'));
    }

    /**
     * Dynamically apply formatting to any date columns.
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        // If the attribute is in the raw attributes list, return the original value
        if (in_array($key, $this->rawAttributes)) {
            return $value;
        }

        // Apply date formatting if needed
        if ($this->shouldFormatDate($key, $value)) {
            return $this->formatDateAttribute($value, $this->shouldUseDateTimeFormat($key));
        }

        return $value;
    }

    /**
     * Check if the given column should be formatted.
     */
    protected function shouldFormatDate($key, $value)
    {
        return !is_null($value)
            && isset($this->formattedDates)
            && is_array($this->formattedDates)
            && in_array($key, $this->formattedDates);
    }
}


