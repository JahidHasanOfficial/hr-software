<?php

namespace App\Services;

use Carbon\Carbon;

class HelperService
{
    public static function formatTime($time)
    {
        if (!$time) return 'N/A';
        return Carbon::parse($time)->format('h:i A');
    }

    public static function formatDate($date)
    {
        if (!$date) return 'N/A';
        return Carbon::parse($date)->format('d M, Y');
    }

    public static function getStatusLabel($status)
    {
        return $status == 1 ? 'ACTIVE' : 'INACTIVE';
    }

    public static function getStatusBadge($status)
    {
        $class = $status == 1 ? 'badge-gradient-success' : 'badge-gradient-danger';
        $label = self::getStatusLabel($status);
        return "<label class='badge {$class}'>{$label}</label>";
    }

    public static function getAttendanceStatus($status)
    {
        switch ($status) {
            case 1: return ['label' => 'PRESENT', 'class' => 'badge-gradient-success'];
            case 2: return ['label' => 'LATE', 'class' => 'badge-gradient-warning'];
            case 3: return ['label' => 'HALF DAY', 'class' => 'badge-gradient-info'];
            case 4: return ['label' => 'LEAVE', 'class' => 'badge-gradient-primary'];
            case 0:
            default: return ['label' => 'ABSENT', 'class' => 'badge-gradient-danger'];
        }
    }
}
