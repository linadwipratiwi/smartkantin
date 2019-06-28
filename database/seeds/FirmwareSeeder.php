<?php

use Carbon\Carbon;
use App\Models\Firmware;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput as Output;

class FirmwareSeeder extends Seeder
{
    /**
     * @var Output
     */
    private $output;

    public function __construct(Output $output)
    {
        $this->output = $output;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->output->writeln('<info>--- Firmware Seeder Started ---</info>');
        $data = array(
            [
                'name' => 'Version Firmware 1', 'code_version' => 'vFirmware.1.0', 'link' => url('upload/firmware/v1.exe'), 'type' => 'firmware'
            ],
            [
                'name' => 'Version UI 1', 'code_version' => 'vUI.1.0', 'link' => url('upload/firmware/v1.exe'), 'type' => 'ui'
            ]
        );
        
        DB::beginTransaction();
        
        Firmware::insert($data);

        DB::commit();
        
        $this->output->writeln('<info>--- Client Seeder Finished ---</info>');
    }
}
