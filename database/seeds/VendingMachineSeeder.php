<?php

use Carbon\Carbon;
use App\Models\StockMutation;
use App\Models\VendingMachine;
use Illuminate\Database\Seeder;
use App\Models\VendingMachineSlot;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput as Output;

class VendingMachineSeeder extends Seeder
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
        $this->output->writeln('<info>--- VendingMachine Seeder Started ---</info>');
        $data = array(
            [
                'name' => 'Vending Machine V1 2019', 'production_year' => '2019', 'client_id' => 1, 'location' => 'Lokasi 1', 'ip' => '127.0.0.1'
            ]
        );
        
        DB::beginTransaction();
        
        VendingMachine::insert($data);
        self::vendingMachineSlot();
        self::vendingMachineStockMutation();

        DB::commit();
        
        $this->output->writeln('<info>--- VendingMachine Seeder Finished ---</info>');
    }

    public static function vendingMachineSlot()
    {
        $data = array(
            [
                'vending_machine_id' => 1,
                'name' => 'SLOT A1',
                'alias' => 'slot_a_1',
                'food_name' => 'Nasi Goreng',
                'hpp' => '8000',
                'selling_price_client' => '12000',
                'profit_client' => '2000',
                'profit_platform' => '3000',
                'selling_price_vending_machine' => '15000',
                'capacity' => '5',
                'stock' => '5',
                'expired_date' => date('Y-m-d') . ' 18:00:00'
            ],
            [
                'vending_machine_id' => 1,
                'name' => 'SLOT A2',
                'alias' => 'slot_a_2',
                'food_name' => 'Sate',
                'hpp' => '10000',
                'selling_price_client' => '13000',
                'profit_client' => '3000',
                'profit_platform' => '2000',
                'selling_price_vending_machine' => '15000',
                'capacity' => '5',
                'stock' => '5',
                'expired_date' => date('Y-m-d') . ' 18:00:00'
            ],
            [
                'vending_machine_id' => 1,
                'name' => 'SLOT A3',
                'alias' => 'slot_a_3',
                'food_name' => 'Nasi Ayam Panggang',
                'hpp' => '8000',
                'selling_price_client' => '12000',
                'profit_client' => '2000',
                'profit_platform' => '3000',
                'selling_price_vending_machine' => '15000',
                'capacity' => '5',
                'stock' => '5',
                'expired_date' => date('Y-m-d') . ' 18:00:00'
            ],
            [
                'vending_machine_id' => 1,
                'name' => 'SLOT A4',
                'alias' => 'slot_a_4',
                'food_name' => 'Nasi Bebek Bakar',
                'hpp' => '8000',
                'selling_price_client' => '12000',
                'profit_client' => '2000',
                'profit_platform' => '3000',
                'selling_price_vending_machine' => '15000',
                'capacity' => '5',
                'stock' => '5',
                'expired_date' => date('Y-m-d') . ' 18:00:00'
            ],
            
        );
        
        VendingMachineSlot::insert($data);
    }

    public static function vendingMachineStockMutation()
    {
        $data = array(
            [
                'vending_machine_id' => 1,
                'vending_machine_slot_id' => 1,
                'client_id' => 1,
                'food_name' => 'Nasi Goreng',
                'hpp' => '8000',
                'selling_price_client' => '12000',
                'stock' => '5',
                'type' => 'stock_mutation',
                'created_by' => 1
            ],
            [
                'vending_machine_id' => 1,
                'vending_machine_slot_id' => 2,
                'client_id' => 1,
                'food_name' => 'Sate',
                'hpp' => '10000',
                'selling_price_client' => '13000',
                'stock' => '5',
                'type' => 'stock_mutation',
                'created_by' => 1
            ],
            [
                'vending_machine_id' => 1,
                'vending_machine_slot_id' => 3,
                'client_id' => 1,
                'food_name' => 'Nasi Ayam Panggang',
                'hpp' => '8000',
                'selling_price_client' => '12000',
                'stock' => '5',
                'type' => 'stock_mutation',
                'created_by' => 1
            ],
            [
                'vending_machine_id' => 1,
                'vending_machine_slot_id' => 4,
                'client_id' => 1,
                'food_name' => 'Nasi Bebek Bakar',
                'hpp' => '8000',
                'selling_price_client' => '12000',
                'stock' => '5',
                'type' => 'stock_mutation',
                'created_by' => 1
            ],
        );

        StockMutation::insert($data);
    }
}
