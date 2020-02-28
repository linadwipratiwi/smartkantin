<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput as Output;

class CustomerSeeder extends Seeder
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
        $this->output->writeln('<info>--- Customer Seeder Started ---</info>');
        $data = array(
            [
                'name' => 'Pringgo Juni S', 'identity_type' => 'KTP', 'identity_number' => '12345', 'register_at_client_id' => 1, 'register_at_vending_machine_id' => 1
            ],
            [
                'name' => 'Mugiwara No Luffy', 'identity_type' => 'KTP', 'identity_number' => '54321', 'register_at_client_id' => 1, 'register_at_vending_machine_id' => 1
            ]
          
        );
        
        DB::beginTransaction();
        
        Customer::insert($data);

        DB::commit();
        
        $this->output->writeln('<info>--- Customer Seeder Finished ---</info>');
    }
}
