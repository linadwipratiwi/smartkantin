<?php

namespace App\Helpers;

use App\Exceptions\AppException;
use Image;
class FileHelper
{
    public static function upload($file, $path)
    {
        if (\Input::file()) {
            $filename = date("YmdHis"). '-' . $file->getClientOriginalName();
            if ($file->move($path, $filename)) {
                return $path.$filename;
            }
            return null;
        }

        return null;
    }

    public static function uploadImageResize($file,$path)
    {
       
        if (\Input::file()) {
            //MENGAMBIL FILE IMAGE DARI FORM
        $filename = "resized". '-' . $file->getClientOriginalName();
        
        //LOOPING ARRAY DIMENSI YANG DI-INGINKAN
        //YANG TELAH DIDEFINISIKAN PADA CONSTRUCTOR
            //MEMBUAT CANVAS IMAGE SEBESAR DIMENSI YANG ADA DI DALAM ARRAY 
            //RESIZE IMAGE SESUAI DIMENSI YANG ADA DIDALAM ARRAY 
            //DENGAN MEMPERTAHANKAN RATIO
            $img=Image::make($file);
            $resizeImage  = Image::make($file)->resize(200,200, function($constraint) {
                $constraint->aspectRatio();
            });
            $size[0]=$resizeImage->height();
            $size[1]=$resizeImage->width();
            $ukuran=($size[0]>$size[1])?$size[1]:$size[0];
            
            $canvas = Image::canvas($ukuran, $ukuran);
            //MEMASUKAN IMAGE YANG TELAH DIRESIZE KE DALAM CANVAS
            $canvas->insert($resizeImage, 'center');
            //SIMPAN IMAGE KE DALAM MASING-MASING FOLDER (DIMENSI)
            $canvas->save($path .'/'.$filename);
            return $path.$filename;
            // if ($file->move($path, $filename)) {
            //     return $path.$filename;
            // }
           
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
