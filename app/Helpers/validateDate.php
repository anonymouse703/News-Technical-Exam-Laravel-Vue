<?php


if (! function_exists('validateDate')) {
    /**
     * Validate a date string in YYYY-MM-DD format.
     *
     * @param  string  $value
     * @return string|null
     */   
    
    function validateDate($value)
    {
        $d = date_create_from_format('Y-m-d', $value);

        if (! $d || $d->format('Y-m-d') !== $value) {
            return 'Please enter a valid date in YYYY-MM-DD format.';
        }

        return null;
    }
}


