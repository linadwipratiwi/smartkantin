<?php

namespace App\Helpers;

use App\User;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\PTPP;
use App\Models\Client;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Customer;
use App\Models\PTPPFile;
use App\Models\Inventory;
use App\Models\Submission;
use App\Helpers\FileHelper;
use App\Models\Certificate;
use App\Helpers\AdminHelper;
use App\Models\PTPPFollowUp;
use Bican\Roles\Models\Role;
use App\Models\SubmissionFile;
use App\Models\VendingMachine;
use App\Models\PTPPVerificator;
use App\Exceptions\AppException;
use App\Models\InventoryHistory;
use App\Models\PTPPFollowUpFile;
use App\Models\PTPPFollowUpDetail;
use Illuminate\Support\Facades\DB;
use App\Models\MaintenanceActivity;
use App\Models\ItemMaintenanceActivity;
use App\Models\MaintenanceActivityHistory;

class AdminHelper
{
    public static function delete($model)
    {
        try{
            $model->delete();
            return true;
        } catch (\Exception $e) {
            throw new AppException("Woops, data can't be delete because is used by another form", 503);
        }
    }

    public static function createClient($request, $id='')
    {
        if (!$id) {
            $user = User::where('username', $request->username)->first();
            if ($user) {
                throw new AppException("Username ini sudah dipakai, silahkan pakai yang lain", 503);
            }

            $user = self::createUserFromClient($request);
        }

        DB::beginTransaction();
        $client = $id ? Client::findOrFail($id) : new Client;
        $client->name = $request->input('name');
        $client->address = $request->input('address');
        $client->company = $request->input('company');
        $client->phone = $request->input('phone');
        $client->user_id = $user->id;
        try{
            $client->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }
        
        DB::commit();
        return $client;
    }

    public static function createUserFromClient($request)
    {
        DB::beginTransaction();

        $user = new User;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->username = $request->username;
        try{
            $user->save();
        }catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        // set role client
        $user->attachRole(2);
        $role = Role::find(2);
        DB::commit();
        
        return $user;
    }

    public static function createCustomer($request, $id='')
    {
        DB::beginTransaction();
        $customer = $id ? Customer::findOrFail($id) : new Customer;
        $customer->name = $request->input('name');
        $customer->identity_type = $request->input('identity_type');
        $customer->identity_number = $request->input('identity_number');
        try{
            $customer->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }
        
        DB::commit();
        return $customer;
    }

    public static function createVendingMachine($request, $id='')
    {
        DB::beginTransaction();
        $vending_machine = $id ? VendingMachine::findOrFail($id) : new VendingMachine;
        $vending_machine->name = $request->input('name');
        $vending_machine->production_year = $request->input('production_year');
        $vending_machine->location = $request->input('location');
        $vending_machine->ip = $request->input('ip');
        $vending_machine->client_id = $request->input('client_id');

        try {
            $vending_machine->save();
        } catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $vending_machine;
    }

    public static function createInventory($request, $id='')
    {
        DB::beginTransaction();
        $inventory = $id ? Inventory::findOrFail($id) : new Inventory;
        $inventory->name = $request->input('name');
        $inventory->brand = $request->input('brand');
        $inventory->type = $request->input('type');
        $inventory->notes = $request->input('notes');
        $inventory->production_year = $request->input('production_year');
        $inventory->location_of_use = $request->input('location_of_use');

        $file = $request->file('file');
        if (isset($file)) {
            $inventory->photo = FileHelper::upload($file, 'uploads/item/');
        }

        try {
            $inventory->save();
        } catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $inventory;
    }

    public static function createInventoryHistory($request, $id='')
    {
        $date = Carbon::createFromFormat('m/d/Y g:i A', $request->input('date'));
        DB::beginTransaction();
        
        $history = $id ? InventoryHistory::findOrFail($id) : new InventoryHistory;
        $history->inventory_id = $request->input('inventory_id');
        $history->stock = $request->input('stock');
        $history->date = $date;
        $history->user_id = auth()->user()->id;

        try {
            $history->save();
        } catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        $check_last_history = InventoryHistory::orderBy('date', 'desc')->first();
        if ($check_last_history) {
            $inventory = $check_last_history->inventory;
            $inventory->stock = $check_last_history->stock;
            $inventory->save();
        }

        DB::commit();
        return $history;
    }

    public static function createCertificate($request, $id='')
    {
        $file = $request->file('file');
        $date_start = Carbon::createFromFormat('m/d/Y g:i A', $request->input('date_start'));
        $date_end = Carbon::createFromFormat('m/d/Y g:i A', $request->input('date_end'));
        
        DB::beginTransaction();
        $certificate = $id ? Certificate::findOrFail($id) : new Certificate;
        if (!$id) {
            $certificate->number = Certificate::number();
        }
        $certificate->name = $request->input('name');
        $certificate->year = $request->input('year');
        $certificate->date_start = $date_start;
        $certificate->date_end = $date_end;
        $certificate->category_id = $request->input('category');
        $certificate->publisher = $request->input('publisher');
        if ($file) {
            $certificate->file = FileHelper::upload($file, 'uploads/certificate/');;
        }

        try{
            $certificate->save();
        }catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $certificate;
    }
    
    public static function createItemMaintenanceActivity($request)
    {
        DB::beginTransaction();
        $id = $request->input('maintenance_activity_id');
        $item_maintenance_activity = $id ? ItemMaintenanceActivity::findOrFail($id) : new ItemMaintenanceActivity;
        $item_maintenance_activity->name = $request->input('name');
        $item_maintenance_activity->item_id = $request->input('item_id');
        $item_maintenance_activity->periode_id = $request->input('periode_id');
        $item_maintenance_activity->category_id = $request->input('category_id');
        $item_maintenance_activity->periode_value = $request->input('periode_value');

        try{
            $item_maintenance_activity->save();
        }catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();

        return $item_maintenance_activity->item;
    }

    public static function createMaintenanceActivity($request, $id='')
    {
        $date = Carbon::createFromFormat('m/d/Y g:i A', $request->input('date'));
        DB::beginTransaction();
        $maintancen_activity = $id ? MaintenanceActivity::findOrFail($id) : new MaintenanceActivity;
        if (!$id) {
            $maintancen_activity->number = MaintenanceActivity::number();
        }
        $maintancen_activity->item_id = $request->input('item_id');
        $maintancen_activity->item_maintenance_activity_id = $request->input('item_maintenance_activity_id');
        $maintancen_activity->notes = $request->input('notes');
        $maintancen_activity->date = $date;
        $maintancen_activity->status = $request->input('status');; // 1: normal, 2: broken
        $maintancen_activity->approval_to = $request->input('approval_to');
        $maintancen_activity->status_approval = $request->input('is_request_approval') ? 1 : 0; // no action
        $maintancen_activity->user_id = auth()->user()->id;

        try{
            $maintancen_activity->save();
        }catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $maintancen_activity;
    }

    public static function createMaintenanceActivityHistory($request, $id='')
    {
        $date = Carbon::createFromFormat('m/d/Y g:i A', $request->input('date'));
        DB::beginTransaction();
        $maintancen_activity = $id ? MaintenanceActivityHistory::findOrFail($id) : new MaintenanceActivityHistory;
        if (!$id) {
            $maintancen_activity->number = MaintenanceActivityHistory::number();
        }
        $maintancen_activity->item_id = $request->input('item_id');
        $maintancen_activity->maintenance_activity_id = $request->input('maintenance_activity_id') ? : null;
        $maintancen_activity->notes = $request->input('notes');
        $maintancen_activity->date = $date;
        $maintancen_activity->is_executor_vendor = $request->input('is_executor_vendor') ? : 0;
        $maintancen_activity->vendor_id = $maintancen_activity->is_executor_vendor ? $request->input('vendor_id') : null;
        $maintancen_activity->executor_name = !$maintancen_activity->is_executor_vendor ? $request->input('executor_name') : null;
        $maintancen_activity->user_id = auth()->user()->id;
        $maintancen_activity->approval_to = $request->input('approval_to');
        $maintancen_activity->status_approval = $request->input('is_request_approval') ? 1 : 0; // no action
        
        try{
            $maintancen_activity->save();
        }catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();
        return $maintancen_activity;
    }

    public static function createSubmission($request, $id="")
    {
        DB::beginTransaction();
        $submission = $id ? Submission::findOrFail($id) : new Submission;
        if (!$id) {
            $submission->number = Submission::number();
        }
        $submission->name = $request->input('item_name');
        $submission->category_id = $request->input('category_id');
        $submission->notes = $request->input('notes');
        $submission->created_by = auth()->user()->id;
        $submission->approval_to_oh = setting('spv_oh');
        $submission->approval_to_spv_epm = setting('spv_epm');
        try{
            $submission->save();
        } catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();

        return $submission;
    }

    public static function createSubmissionFile($submission, $request)
    {
        $name = $request->input('file_name');
        $file = $request->file('file');
        
        for ($i=0; $i < count($name); $i++) { 
            $submission_file = new SubmissionFile;
            $submission_file->submission_id = $submission->id;
            $submission_file->name = $name[$i];
            $submission_file->file = FileHelper::upload($file[$i], 'uploads/submission-file/');
            try{
                $submission_file->save();
            } catch (\Exception $e){
                throw new AppException("Failed to save data", 503);
            }
        }
    }

    public static function createSubmissionFileSingle($submission, $request, $id="")
    {
        $name = $request->input('file_name');
        $file = $request->file('file');
        
        $submission_file = $id ? SubmissionFile::findOrFail($id) : new SubmissionFile;
        $submission_file->submission_id = $submission->id;
        $submission_file->name = $name;
        if ($file) {
            $submission_file->file = FileHelper::upload($file, 'uploads/submission-file/');
        }
        try{
            $submission_file->save();
        } catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }
    }

    /**
     * PTPP
     */

    public static function createPtpp($request, $id="")
    {
        $created_at = Carbon::createFromFormat('m/d/Y g:i A', $request->input('created_at'));
        DB::beginTransaction();
        $ptpp = $id ? PTPP::findOrFail($id) : new PTPP;
        if (!$id) {
            $ptpp->number = PTPP::number();
        }
        $ptpp->from = $request->input('from');
        $ptpp->to_function = $request->input('to_function');
        $ptpp->category_id = $request->input('category');
        $ptpp->location = $request->input('location');
        $ptpp->notes = $request->input('notes');
        $ptpp->created_at = $created_at;
        $ptpp->created_by = auth()->user()->id;
        $ptpp->approval_to_oh = setting('spv_oh');
        $ptpp->approval_to_spv_rsd = setting('spv_rsd');
        try{
            $ptpp->save();
        } catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();

        return $ptpp;
    }

    public static function createPtppFile($ptpp, $request)
    {
        $name = $request->input('file_name');
        $file = $request->file('file');
        
        for ($i=0; $i < count($name); $i++) { 
            $ptpp_file = new PTPPFile;
            $ptpp_file->ptpp_id = $ptpp->id;
            $ptpp_file->name = $name[$i];
            $ptpp_file->file = FileHelper::upload($file[$i], 'uploads/ptpp-file/');
            try{
                $ptpp_file->save();
            } catch (\Exception $e){
                throw new AppException("Failed to save data", 503);
            }
        }
    }

    public static function createPtppFileSingle($ptpp, $request, $id="")
    {
        $name = $request->input('file_name');
        $file = $request->file('file');
        
        $ptpp_file = $id ? PTPPFile::findOrFail($id) : new PTPPFile;
        $ptpp_file->ptpp_id = $ptpp->id;
        $ptpp_file->name = $name;
        if ($file) {
            $ptpp_file->file = FileHelper::upload($file, 'uploads/ptpp-file/');
        }
        try{
            $ptpp_file->save();
        } catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }
    }

    // Follow UP
    public static function createFollowUp($request, $id="")
    {
        $date = Carbon::createFromFormat('m/d/Y g:i A', $request->input('date'));
        DB::beginTransaction();
        $follow_up = $id ? PTPPFollowUp::findOrFail($id) : new PTPPFollowUp;
        $follow_up->ptpp_id = $request->input('ptpp_id');
        $follow_up->notes = $request->input('notes');
        $follow_up->date = $date;
        $follow_up->created_by = auth()->user()->id;
        $follow_up->approval_to_spv_epm = setting('spv_epm');
        $follow_up->status_approval_to_spv_epm = 0;
        try{
            $follow_up->save();
        } catch (\Exception $e){
            throw new AppException("Failed to save data", 503);
        }

        DB::commit();

        return $follow_up;
    }

    public static function createFollowUpDetail($follow_up, $request, $id="")
    {
        $problem = $request->input('problem');
        $prevention = $request->input('prevention');
        $pic = $request->input('pic');
        $date_finish = $request->input('date_finish');

        // If Edit, clear old detail follow up
        if ($id) {
            $details = PTPPFollowUpDetail::where('ptpp_follow_up_id', $id)->delete();
        }

        for ($i=0; $i < count($problem); $i++) {
            $date = Carbon::createFromFormat('m/d/Y g:i A', $date_finish[$i]);
            $follow_up_file = new PTPPFollowUpDetail;
            $follow_up_file->ptpp_follow_up_id = $follow_up->id;
            $follow_up_file->problem = $problem[$i];
            $follow_up_file->prevention = $prevention[$i];
            $follow_up_file->pic = $pic[$i];
            $follow_up_file->date_finish = $date;
            try{
                $follow_up_file->save();
            } catch (\Exception $e){
                throw new AppException("Failed to save data", 503);
            }
        }
    }

    public static function createFollowUpFile($follow_up, $request, $id="")
    {
        $name = $request->input('file_name');
        
        // If Edit, clear old file follow up
        if ($id) {
            $files = PTPPFollowUpFile::where('ptpp_follow_up_id', $id)->delete();
        }

        for ($i=0; $i < count($name); $i++) {
            $follow_up_file = new PTPPFollowUpFile;
            $follow_up_file->ptpp_follow_up_id = $follow_up->id;
            $follow_up_file->name = $name[$i];
            try{
                $follow_up_file->save();
            } catch (\Exception $e){
                throw new AppException("Failed to save data", 503);
            }
        }
    }

    public static function createPtppVerificator($request, $id='')
    {
        DB::beginTransaction();
        $verificator = $id ? PTPPVerificator::findOrFail($id) : new PTPPVerificator;
        $verificator->ptpp_id = $request->input('ptpp_id');
        $verificator->status = $request->input('status');
        $verificator->no_ptpp_new = $request->input('no_ptpp_new');
        $verificator->approval_to_oh = setting('spv_oh');
        $verificator->status_approval_to_oh = 0;
        try{
            $verificator->save();
        } catch (\Exception $e) {
            throw new AppException("Failed to save data", 503);
        }
        
        DB::commit();
        return $verificator;
    }
}
