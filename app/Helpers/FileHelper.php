<?php

namespace App\Helpers;

use App\Exceptions\AppException;

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
    
    public static function xlsValidate()
    {
        $file = $_FILES['file']['name'];
        $file_part = pathinfo($file);
        $extension = $file_part['extension'];
        $support_extention = array('xls', 'xlsx', 'xlsm');
        if (! in_array($extension, $support_extention)) {
            throw new AppException('FILE FORMAT NOT ACCEPTED, PLEASE USE XLS OR XLSX EXTENTION');
        }
    }
}
