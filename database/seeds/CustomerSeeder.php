<?php

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Database\Seeder;
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
                'name' => 'Pringgo Juni S', 'identity_type' => 'KTP', 'identity_type' => '12345'
            ],
            [
                'name' => 'Mugiwara No Luffy', 'identity_type' => 'KTP', 'identity_type' => '54321'
            ]
        );
        
        DB::beginTransaction();
        
        Customer::insert($data);

        DB::commit();
        
        $this->output->writeln('<info>--- Customer Seeder Finished ---</info>');
    }
}
