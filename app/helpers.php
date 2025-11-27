<?php

if (!function_exists('user_photo')) {
    function user_photo($user) {
        if ($user && $user->foto_path) {
            return route('file.user', basename($user->foto_path));
        }
        return null;
    }
}

if (!function_exists('location_photo')) {
    function location_photo($lokasi) {
        if ($lokasi && $lokasi->foto_path) {
            return route('file.location', basename($lokasi->foto_path));
        }
        return null;
    }
}

if (!function_exists('unit_photo')) {
    function unit_photo($unit) {
        if ($unit && $unit->foto_path) {
            return route('file.unit', basename($unit->foto_path));
        }
        return null;
    }
}