<?php

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput as Output;

class CategorySeeder extends Seeder
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
        $this->output->writeln('<info>--- Category Seeder Started ---</info>');
        $array_data = array(
            'item' => ['Operator check', 'Predictive Maintenance', 'Preventive Maintenance'],
            'submission' => ['Engineering Planning Maintenance', 'Sales General Admin', 'Receiving Storage Distribution', 'Healthy Safety Security Environment' ],
            'certificate' => ['SKPP', 'SKPA', 'Grounding'],
            'ptpp' => ['Keluhan Pelanggan', 'Audit Internal', 'Tinjauan Manajemen', 'Saran/Keluhan Masyarakat', 'Usulan/Saran', 'Penggantian/Perbaikan']
        );

        DB::beginTransaction();
        foreach($array_data as $index => $categories) {
            foreach ($categories as $value) {
                $category = new Category;
                $category->name = $value;
                $category->type = $index;
                $category->save();
            }
        }
        DB::commit();
        $this->output->writeln('<info>--- Category Seeder Finished ---</info>');
    }
}
