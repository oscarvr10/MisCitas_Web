<?php

namespace App\Utils;

class HoursHelper
{
    public static function generateHours(int $fromHour, int $toHour, bool $morning)
    {
        $hours = [];
        for ($i = $fromHour; $i <= $toHour; $i++) {
            if ($morning) {
                $hours[] = array(
                    'value' => ($i < 10 ? '0' : '') . $i . ':00',
                    'data' => $i . ':00'
                );
                $hours[] = array(
                    'value' => ($i < 10 ? '0' : '') . $i . ':30',
                    'data' => $i . ':30'
                );
            } else {
                $hours[] = array(
                    'value' => ($i + 12) . ':00',
                    'data' => $i . ':00'
                );
                $hours[] = array(
                    'value' => ($i + 12) . ':30',
                    'data' => $i . ':30'
                );
            }
        }
        return $hours;
    }
}
