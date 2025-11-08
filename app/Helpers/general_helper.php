<?php

function greeting()
{
    date_default_timezone_set('Asia/Jakarta');
    $hour = date('H');

    if ($hour >= 5 && $hour < 12) {
        return 'Selamat Pagi';
    } elseif ($hour >= 12 && $hour < 15) {
        return 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
        return 'Selamat Sore';
    } else {
        return 'Selamat Malam';
    }
}