<?php

namespace api\helpers;

class DateHelper
{
    public static function formatApi($timestamp)
    {
        return $timestamp ? date(DATE_RFC3339, $timestamp) : null;
    }

    public static function formatDate($timestamp){
        return date("Y-m-d h:i:s",$timestamp);
    }
}