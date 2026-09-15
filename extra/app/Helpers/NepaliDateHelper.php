<?php

namespace App\Helpers;

use Carbon\Carbon;

class NepaliDateHelper
{
    /**
     * Convert AD date to BS date
     * This is a simplified conversion. For production, use a proper library like nepali-date
     */
    public static function convertToBS($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        // Simplified BS conversion (offset of ~56 years and 8.5 months)
        // In production, use a proper library like 'nepalidate/nepali-date'
        $bsYear = $date->year + 56;
        $bsMonth = $date->month + 8;
        $bsDay = $date->day + 15;
        
        if ($bsMonth > 12) {
            $bsMonth -= 12;
            $bsYear++;
        }
        
        if ($bsDay > 32) {
            $bsDay -= 32;
            $bsMonth++;
            if ($bsMonth > 12) {
                $bsMonth -= 12;
                $bsYear++;
            }
        }
        
        return sprintf('%04d-%02d-%02d', $bsYear, $bsMonth, $bsDay);
    }
    
    /**
     * Get current BS date
     */
    public static function todayBS()
    {
        return self::convertToBS();
    }
}
