<?php

use Illuminate\Database\Seeder;
use App\Models\StatusTransaction;
use App\StatusTransaction as AppStatusTransaction;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput as Output;


class StatusTransactionSeeder extends Seeder
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
        //
        $this->output->writeln('<info>--- StatusTransaction Seeder Started ---</info>');
        $data = array(
            [
                'kode_status'=>1,
                'keterangan' => 'success delivered'
            ],
            [
                'kode_status'=>0,
                'keterangan' => 'failed'
            ],
            [
                'kode_status'=>3,
                'keterangan' => 'waiting deliver'
            ],
            [
                'kode_status'=>2,
                'keterangan' => 'waiting payment'
            ],
            [
                'kode_status'=>4,
                'keterangan' => 'canceled'

            ]
          
        );
        
        DB::beginTransaction();
        
        StatusTransaction::insert($data);

        DB::commit();
        
        $this->output->writeln('<info>--- StatusTransaction Seeder Finished ---</info>');
    }
}
