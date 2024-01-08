<?php

if (!function_exists('convertExcelDateToTimestamp')) {
    function convertExcelDateToTimestamp($excelDate, $format = 'Y-m-d')
    {
        // Excel stores dates as the number of days since 1900-01-01 (Excel's base date)
        $unixTimestamp = ($excelDate - 25569) * 86400; // Convert Excel date to Unix timestamp

        $dateOrder = new \DateTime();
        $dateOrder->setTimestamp($unixTimestamp);

        return $dateOrder->format($format);
    }
}
