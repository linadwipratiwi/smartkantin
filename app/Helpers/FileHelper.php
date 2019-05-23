<?php

namespace App\Helpers;

class FileHelper
{
    public static function upload($file, $path)
    {
        if(\Input::file()) {
            $filename = date("YmdHis"). '-' . $file->getClientOriginalName();
            if($file->move($path, $filename)){
                return $path.$filename;
            }
            return null;
        }

        return null;
    }
    
}
