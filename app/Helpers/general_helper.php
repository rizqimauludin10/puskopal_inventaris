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

function format_tanggal_singkat($date, $with_day = false)
{
    if (empty($date) || $date == '0000-00-00') return '-';

    $hari = [
        1 => 'Sen',
        2 => 'Sel',
        3 => 'Rab',
        4 => 'Kam',
        5 => 'Jum',
        6 => 'Sab',
        7 => 'Min'
    ];

    $bulan = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agu',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des'
    ];

    $timestamp = strtotime($date);
    $tgl = date('j', $timestamp);
    $bln = $bulan[(int)date('n', $timestamp)];
    $thn = date('Y', $timestamp);

    $formatted = "$tgl $bln $thn";

    if ($with_day) {
        $hariIndex = (int)date('N', $timestamp);
        $formatted = $hari[$hariIndex] . ', ' . $formatted;
    }

    return $formatted;
}

function format_tanggal_full($date, $with_day = false)
{
    if (empty($date) || $date == '0000-00-00') return '-';

    $hari = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu'
    ];

    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $timestamp = strtotime($date);
    $tgl = date('j', $timestamp);
    $bln = $bulan[(int)date('n', $timestamp)];
    $thn = date('Y', $timestamp);

    $formatted = "$tgl $bln $thn";

    if ($with_day) {
        $hariIndex = (int)date('N', $timestamp);
        $formatted = $hari[$hariIndex] . ', ' . $formatted;
    }

    return $formatted;
}