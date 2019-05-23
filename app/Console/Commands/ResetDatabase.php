<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem\Filesystem;

class ResetDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset database and seeding data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        foreach(DB::select('SHOW TABLES') as $table) {
            $table_array = get_object_vars($table);
            \Schema::drop($table_array[key($table_array)]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true, '--class' => 'DatabaseSeeder']);
        DB::commit();
    }
}
