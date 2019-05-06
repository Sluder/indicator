<?php

namespace Sluder\Indicator;

class Indicator
{
    /**
     * Simple Moving Average
     */
    public static function sma($ticks, $length)
    {
        $ticks = array_splice(array_reverse($ticks), 0, $length);

        return (array_sum($ticks) / count($ticks));
    }

    /**
     * Triple Moving Average
     */
    public static function tma($ticks, $length_low, $length_medium, $length_high)
    {
        $ticks = array_reverse($ticks);

        $low_ticks = array_splice($ticks, 0, $length_low);
        $medium_ticks = array_splice($ticks, 0, $length_medium);
        $high_ticks = array_splice($ticks, 0, $length_high);

        return [
            (array_sum($low_ticks) / count($low_ticks)),
            (array_sum($medium_ticks) / count($medium_ticks)),
            (array_sum($high_ticks) / count($high_ticks))
        ];
    }

    /**
     * Exponential Moving Average
     */
    public static function ema($ticks)
    {
        $alpha = 2 / (count($ticks) + 1);
        $ema_history = [$ticks[0]];

        for ($i = 1; $i < count($ticks); $i++) {
            $ema_history[] = ($alpha * $ticks[$i]) + ((1 - $alpha) * $ema_history[$i - 1]);
        }

        return end($ema_history);
    }

    /**
     * Triple Exponential Moving Average
     */
    public static function tema($ticks, $length_low, $length_medium, $length_high)
    {
        $tema = [];
        $ticks = array_reverse($ticks);

        foreach ([$length_low, $length_medium, $length_high] as $length) {
            $ticks_splice = array_splice($ticks, 0, $length);
            $ticks_splice = array_reverse($ticks_splice);

            $alpha = 2 / (count($length) + 1);
            $ema_history = [$ticks_splice[0]];

            for ($i = 1; $i < count($ticks_splice); $i++) {
                $ema_history[] = ($alpha * $ticks_splice[$i]) + ((1 - $alpha) * $ema_history[$i - 1]);
            }

            $tema[] = end($ema_history);
        }

        return $tema;
    }

    /**
     * Moving Average Convergence Divergence
     */
    public static function macd($ticks, $length_low, $length_high)
    {
        $ticks = array_reverse($ticks);

        $low_ema = self::ema(array_reverse(array_splice($ticks, 0, $length_low)));
        $high_ema = self::ema(array_reverse(array_splice($ticks, 0, $length_high)));

        return $low_ema - $high_ema;
    }
}