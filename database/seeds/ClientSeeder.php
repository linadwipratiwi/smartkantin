<?php

use Carbon\Carbon;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput as Output;

class ClientSeeder extends Seeder
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
        $this->output->writeln('<info>--- Client Seeder Started ---</info>');
        $data = array(
            [
                'name' => 'YDFS', 'phone' => '085736676648', 'company' => 'YDFS', 'address' => 'Surabaya', 'user_id' => 3
            ]
        );
        
        DB::beginTransaction();
        
        Client::insert($data);

        DB::commit();
        
        $this->output->writeln('<info>--- Client Seeder Finished ---</info>');
    }
}
