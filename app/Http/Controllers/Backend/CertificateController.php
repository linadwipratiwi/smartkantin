<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Category;
use App\Models\Certificate;
use App\Helpers\AdminHelper;
use App\Helpers\ExcelHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        access_is_allowed('read.certificate');

        $category = \Input::get('category');
        $status = \Input::get('status');

        $view = view('backend.certificate.index');
        $view->certificates = Certificate::where(function($q) use ($category, $status) {
            if ($category) $q->where('category_id', $category);
            if ($status) {
                if ($status == 1) {
                    $q->where('date_end', '>=', Carbon::now());
                }

                if ($status == 2) {
                    $q->where('date_end', '<=', Carbon::now());
                }
            }
        })->paginate(25);
        $view->categories = Category::certificate()->get();

        return $view;
    }

    public function create($ref_id=null)
    {
        access_is_allowed('create.certificate');

        $view = view('backend.certificate.create');
        $view->categories = Category::certificate()->get();
        return $view;
    }

    public function store(Request $request)
    {
        access_is_allowed('create.certificate');

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'year' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'publisher' => 'required',
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            toaster_error('create form failed');
            return redirect('certificate/create')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createCertificate($request);
        toaster_success('create form success');
        return redirect('certificate');
    }

    public function edit($id)
    {
        access_is_allowed('update.certificate');

        $view = view('backend.certificate.edit');
        $view->certificate = Certificate::findOrFail($id);
        $view->categories = Category::certificate()->get();


        return $view;
    }

    public function update(Request $request, $id)
    {
        access_is_allowed('update.certificate');

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'year' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'publisher' => 'required',
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            toaster_error('update form failed');
            return redirect('certificate/'.$id.'/edit')->withErrors($validator)
                ->withInput();
        }

        AdminHelper::createCertificate($request, $id);
        toaster_success('update form success');
        return redirect('certificate');
    }

    public function destroy($id)
    {
        access_is_allowed('delete.certificate');

        $type = Certificate::findOrFail($id);
        $delete = AdminHelper::delete($type);
        
        toaster_success('delete form success');
        return redirect('certificate');
    }

    public function report(Request $request) 
    {
        $category = \Input::get('category');
        $status = \Input::get('status');
        $certificates = Certificate::where(function($q) use ($category, $status) {
            if ($category) $q->where('category_id', $category);
            if ($status) {
                if ($status == 1) {
                    $q->where('date_end', '>=', Carbon::now());
                }

                if ($status == 2) {
                    $q->where('date_end', '<=', Carbon::now());
                }
            }
        })->get();
        $content = array(array('NAME', 'CATEGORY', 'YEAR', 'TGL. MULAI BERLAKU', 'TGL. AKHIR', 'PUBLISHER'));
        foreach ($certificates as $certificate) {
            array_push($content, [$certificate->name, $certificate->category->name,
            $certificate->year, \App\Helpers\DateHelper::formatView($certificate->date_start), \App\Helpers\DateHelper::formatView($certificate->date_end), 
            $certificate->publisher]);
        }

        $file_name = 'SERTIFIKAT ' .date('YmdHis');
        $header = 'LAPORAN SERTIFIKAT ';
        ExcelHelper::excel($file_name, $content, $header);
    }
}
