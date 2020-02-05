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
            $canvas = Image::canvas(200, 200);
            //RESIZE IMAGE SESUAI DIMENSI YANG ADA DIDALAM ARRAY 
            //DENGAN MEMPERTAHANKAN RATIO
            $resizeImage  = Image::make($file)->resize(200 ,200, function($constraint) {
                $constraint->aspectRatio();
            });
         
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
        
        
        //SIMPAN DATA IMAGE YANG TELAH DI-UPLOAD
        // Image_uploaded::create([
        //     'name' => $fileName,
        //     'dimensions' => implode('|', $this->dimensions),
        //     'path' => $this->path
        // ]);
        // return redirect()->back()->with(['success' => 'Gambar Telah Di-upload']);
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
