<?php
namespace Helpers;

class Date{

    protected $time;

    public function __construct() {
        // Atur zona waktu ke Jakarta
        date_default_timezone_set('Asia/Jakarta');
    }

    protected static function zone() {
        $zone = env('TIMEZONE');
        return date_default_timezone_set($zone);
    }
    
    public static function Now()
    {
        self::zone();
        $date = date('Y-m-d H:i:s');
        return $date;
    }

    public static function Day()
    {
        self::zone();
        $date = date('d');
        return $date;
    } 

    public static function Month()
    {
        self::zone();
        $date = date('m');
        return $date;
    }

    public static function Year()
    {
        self::zone();
        $date = date('Y');
        return $date;
    }

    public static function parse($parameter)
    {
        $time = is_numeric($parameter) ? $parameter : strtotime($parameter);
        $instance = new self();
        $instance->time = $time;
        return $instance;
    }

    public function format($format)
    {
        return date($format,self::zone());
    }

    public function startOfDay()
    {
        self::zone();
        return date('Y-m-d 00:00:00',self::zone());
    }

    public function endOfDay()
    {
        self::zone();
        return date('Y-m-d 23:59:59',self::zone());
    }

    public function startOfMonth()
    {
        self::zone();
        return date('Y-m-01 00:00:00', $this->time);
    }

    public function endOfMonth()
    {
        self::zone();
        return date('Y-m-t 23:59:59', $this->time);
    }

    public function toDate()
    {
        self::zone();
        return date('Y-m-d',$this->time);
    }

    public function toTime()
    {
        self::zone();
        return date('H:i:s',$this->time);
    }

    public function isToday() {
        return date('Y-m-d', $this->time) === date('Y-m-d');
    }

    public static function DayNow()
    {
        setTime();
        $date = date('l');
        return $date;
    }

    public static function DayName($dateInput = null)
    {
        self::zone();
        $timestamp = $dateInput ? strtotime($dateInput) : time();
        $dayName = date('l', $timestamp);
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return $days[$dayName] ?? $dayName;
    }

    public function isPast() {
        return $this->time < time();
    }

    public function isFuture() {
        return $this->time > time();
    }

    public static function isValidDateRange($date, $daysBefore = 14, $daysAfter = 14)
    {
        self::zone();

        $today = date('Y-m-d', strtotime($date));
        $minDate = date('Y-m-d', strtotime("-{$daysBefore} days"));
        $maxDate = date('Y-m-d', strtotime("+{$daysAfter} days"));

        return ($date >= $minDate && $date <= $maxDate);
    }
}