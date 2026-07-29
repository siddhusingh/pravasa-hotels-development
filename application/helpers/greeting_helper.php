<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_time_based_greeting')) {
    function get_time_based_greeting($timezone = 'Asia/Kolkata')
    {
        $current_time = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $hour = (int) $current_time->format('G');

        if ($hour >= 5 && $hour < 12) {
            return 'Good Morning';
        }

        if ($hour >= 12 && $hour < 17) {
            return 'Good Afternoon';
        }

        if ($hour >= 17 && $hour < 21) {
            return 'Good Evening';
        }

        return 'Good Night';
    }
}
