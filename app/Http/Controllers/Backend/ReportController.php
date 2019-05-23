<?php

namespace App\Http\Controllers\Backend;

use App\Models\Guest;
use App\Models\Employee;
use App\Models\CardAccess;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Models\WorkingPermit;
use App\Models\GuestVisitTransaction;
use App\Http\Controllers\Controller;
use App\Models\GateTransportationReport;

class ReportController extends Controller
{
    public function employee()
    {
        $view = view('backend.report.employee');
        $view->employees =  Employee::all();
        return $view;
    }

    public function guest(Request $request)
    {
        $view = view('backend.report.guest');
        $view->guest_visit_transactions =  GuestVisitTransaction::search($request)->paginate(25);
        return $view;
    }

    public function workingPermit(Request $request)
    {
        $view = view('backend.report.working-permit');
        $view->working_permits =  WorkingPermit::search($request)->get();
        return $view;
    }

    public function gate(Request $request)
    {
        $view = view('backend.report.gate');
        $view->reports =  GateTransportationReport::orderBy('created_at', 'desc')->get();
        return $view;
    }

    public function employeeDownload(Request $request) 
    {
        $employess = Employee::all();
        $content = array(array('NAMA',  'NIP', 'KARTU', 'TGL. LAHIR', 'ALAMAT', 'TELEPON', 'EMAIL', 'POSISI/JABATAN', 'BAGIAN', 'BIDANG'));
        foreach ($employess as $employes) {
            $card = CardAccess::findOrFail($employes->card_access_id);
            array_push($content, [$employes->name, $card->card_number,$employes->nip, $employes->date_of_birth, $employes->address, $employes->phone,
            $employes->email, $employes->position_in_company, $employes->district->name, $employes->area->name ]);
        }

        $file_name = 'PEGAWAI ' .date('YmdHis');
        $header = 'LAPORAN PEGAWAI ';
        self::excel($file_name, $content, $header);
    }

    public function guestDownload(Request $request) 
    {
        $visit = GuestVisitTransaction::search($request)->get();
        $content = array(array('TGL. KUNJUNGAN', 'PERUSAHAAN', 'KEPERLUAN', 'KARYAWAN TUJUAN', 'JUMLAH ORANG'));
        foreach ($visit as $transaction) {
            array_push($content, [DateHelper::formatView($transaction->date_entry, true), $transaction->company->name,
            $transaction->purpose, $transaction->employee->name, $transaction->guests->count()]);
        }

        $file_name = 'TAMU ' .date('YmdHis');
        $header = 'LAPORAN TAMU ';
        self::excel($file_name, $content, $header);
    }

    public function workingPermitDownload(Request $request) 
    {
        $working_permits = WorkingPermit::search($request)->get();
        $content = array(array('NO. SAFETY PERMIT',  'PERUSAHAAN', 'NO. MEMO/PO/SPK', 'DURASI', 'SAFETY MAN', 'CP', 'LOKASI', 'PEKERJAAN'));
        foreach ($working_permits as $working_permit) {
            $duration = DateHelper::formatView($working_permit->date_start_working) .'-'. DateHelper::formatView($working_permit->date_finish_working);
            array_push($content, [$working_permit->no_safety_permit, $working_permit->company->name,
            $working_permit->no_memo_spk, $working_permit->person_in_charge, $duration, $working_permit->safety_man, $working_permit->contact_person,
            $working_permit->location, $working_permit->job_desc]);
        }

        $file_name = 'WORKING PERMIT ' .date('YmdHis');
        $header = 'LAPORAN WORKING PERMIT ';
        self::excel($file_name, $content, $header);
    }

    public static function excel($file_name, $data, $header) 
    {
        \Excel::create($file_name, function ($excel) use ($data, $header)  {
            # Sheet Tim
            $excel->sheet($header, function ($sheet) use ($data, $header)  {
                $sheet->setWidth(array(
                    'A' => 25,
                    'B' => 25
                ));

                // MERGER COLUMN
                $sheet->mergeCells('A1:G1', 'center');
                $sheet->cell('A1:J2', function ($cell) {
                    // Set font
                    $cell->setFont(array(
                        'family'     => 'Times New Roman',
                        'size'       => '12',
                    ));
                });
                $sheet->cell('A1', function ($cell) use ($header) {
                    $cell->setValue(strtoupper($header));
                });
                
                $sheet->fromArray($data, null, 'A2', false, false);
            });

        })->export('xls');
    }
}
