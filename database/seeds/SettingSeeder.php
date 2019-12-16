<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput as Output;

class SettingSeeder extends Seeder
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
        $this->output->writeln('<info>--- Settings Seeder Started ---</info>');
        $data = array(
            0 => [
                'name' => 'Tipe Pengambilan Profit',
                'description' => 'Jenis platform mengambil keuntungan dari setiap transaksi',
                'key' => 'type_take_profit',
                'value' => 'by_value', // by_value dan by_percent
                'input_type' => 'string',
            ],
            1 => [
                'name' => 'Profit Platform dalam persen ',
                'description' => 'Keuntungan dalam persen',
                'key' => 'type_take_profit_by_percent',
                'value' => 10,
                'input_type' => 'integer',
            ],
            2 => [
                'name' => 'Profit Platform dalam value',
                'description' => 'Keuntungan platform bentuk value',
                'key' => 'type_take_profit_by_value',
                'value' => 3000,
                'input_type' => 'integer',
            ]
        );

        DB::beginTransaction();
        for ($i=0; $i<count($data); $i++) {
            \DB::table('settings')->insert(
                $data[$i]
            );
        }

        DB::commit();
        $this->output->writeln('<info>--- Settings Seeder Finished ---</info>');
    }
}
