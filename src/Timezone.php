<?php

namespace JamesMills\LaravelTimezone;

use Carbon\Carbon;
use JamesMills\LaravelTimezone\Traits\TimezoneTrait;

class Timezone {

    use TimezoneTrait;

    /**
     * @param  Carbon|null  $date
     * @param  null  $format
     * @param  bool  $format_timezone
     * @return string
     */
    public function convertToLocal(?Carbon $date, $format = null, $format_timezone = false): string {
        if (is_null($date)) {
            return 'Empty';
        }

        $date->setTimezone($this->getUserTimezone());

        if (is_null($format)) {
            return $date->format(config('timezone.format'));
        }

        $formatted_date_time = $date->format($format);

        if ($format_timezone) {
            return $formatted_date_time . ' ' . $this->formatTimezone($date);
        }

        return $formatted_date_time;
    }

    /**
     * @param $date
     * @return Carbon
     */
    public function convertFromLocal($date): Carbon {
        $tz = auth()->user() ? auth()->user()->timezone : config('app.timezone');
        return Carbon::parse($date, $tz)->setTimezone('UTC');
    }

    /**
     * @param  Carbon  $date
     * @return string
     */
    private function formatTimezone(Carbon $date): string {
        $timezone = $date->format('e');
        $parts = explode('/', $timezone);

        if (count($parts) > 1) {
            return str_replace('_', ' ', $parts[1]) . ', ' . $parts[0];
        }

        return str_replace('_', ' ', $parts[0]);
    }
}
