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
                'keterangan' => 'success delivered'
            ],
            [
                'keterangan' => 'failed'
            ],
            [
                'keterangan' => 'waiting deliver'
            ],
            [
                'keterangan' => 'waiting payment'
            ],
            [
                'keterangan' => 'canceled'

            ]
          
        );
        
        DB::beginTransaction();
        
        StatusTransaction::insert($data);

        DB::commit();
        
        $this->output->writeln('<info>--- StatusTransaction Seeder Finished ---</info>');
    }
}
