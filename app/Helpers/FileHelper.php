<?php

namespace App\Helpers;

use Carbon\Carbon;

class FileHelper
{

    public static function fileToBase64($file)
    {
        return base64_encode(file_get_contents($file));
    }

    public static function fileToHex($file)
    {
        $data = unpack('H*hex', self::fileToBin($file));

        return $data;
    }

    public static function fileToBin($file)
    {
        $data = file_get_contents($file);

        return $data;
    }

    public static function getFilename($file)
    {
        $oldName = $file->getClientOriginalName();
        $extension = self::getExtension($file);

        return str_replace($extension, '-' . Carbon::now()->timestamp . $extension, $oldName);
    }

    public static function getExtension($file)
    {
        return '.' . $file->getClientOriginalExtension();
    }
}
