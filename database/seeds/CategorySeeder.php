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
            'version vending machine' => ['V.1.0']
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
