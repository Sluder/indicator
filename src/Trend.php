<?php

namespace Sluder\Indicator;

use Sluder\Indicator\Exceptions\TrendException;

class Trend
{
    /**
     * Simple Moving Average
     *
     * @throws TrendException
     */
    public static function sma($ticks, $length)
    {
        if (count($ticks) < $length) {
            throw new TrendException("Ticks length needs to be >= EMA length");
        }
        $ticks = array_splice(array_reverse($ticks), 0, $length);

        return array_sum($ticks) / count($ticks);
    }

    /**
     * Triple Moving Average
     *
     * @throws TrendException
     */
    public static function tma($ticks, $length_low, $length_medium, $length_high)
    {
        if ($length_low >= $length_medium || $length_low >= $length_high || $length_medium >= $length_high) {
            throw new TrendException("TMA lengths need to fit format length_low < length_medium < length_high");
        }
        
        $ticks = array_reverse($ticks);

        $low_ticks = array_splice($ticks, 0, $length_low);
        $medium_ticks = array_splice($ticks, 0, $length_medium);
        $high_ticks = array_splice($ticks, 0, $length_high);

        return [
            array_sum($low_ticks) / count($low_ticks),
            array_sum($medium_ticks) / count($medium_ticks),
            array_sum($high_ticks) / count($high_ticks)
        ];
    }

    /**
     * Exponential Moving Average
     */
    public static function ema($ticks, $get_history = false)
    {
        $alpha = 2 / (count($ticks) + 1);
        $ema_history = [$ticks[0]];

        for ($i = 1; $i < count($ticks); $i++) {
            $ema_history[] = ($alpha * $ticks[$i]) + ((1 - $alpha) * $ema_history[$i - 1]);
        }

        return $get_history ? $ema_history : end($ema_history);
    }

    /**
     * Triple Exponential Moving Average
     *
     * @throws TrendException
     */
    public static function tema($ticks, $length_low, $length_medium, $length_high, $get_history = false)
    {
        if ($length_low >= $length_medium || $length_low >= $length_high || $length_medium >= $length_high) {
            throw new TrendException("TEMA lengths need to fit format length_low < length_medium < length_high");
        }

        $tema = [];

        foreach ([$length_low, $length_medium, $length_high] as $length) {
            $ticks_splice = array_splice(array_reverse($ticks), 0, $length);

            $alpha = 2 / (count($length) + 1);
            $ema_history = [$ticks_splice[0]];

            for ($i = 1; $i < count($ticks_splice); $i++) {
                $ema_history[] = ($alpha * $ticks_splice[$i]) + ((1 - $alpha) * $ema_history[$i - 1]);
            }

            $tema[] = $get_history ? $ema_history : end($ema_history);
        }

        return $tema;
    }
}