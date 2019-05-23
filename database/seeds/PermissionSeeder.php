<?php

use App\User;
use Bican\Roles\Models\Role;
use Illuminate\Database\Seeder;
use App\Helpers\PermissionHelper;
use Bican\Roles\Models\Permission;
use Symfony\Component\Console\Output\ConsoleOutput as Output;

class PermissionSeeder extends Seeder
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
        $this->output->writeln('<info>--- Permission Seeder Started ---</info>');
        self::permission();
        $this->output->writeln('<info>--- Permission Seeder Finished ---</info>');
        
    }

    public function permission()
    {
        $this->output->writeln('<info>--- Permission Seeder Started ---</info>');

        $group = 'CHECKLIST';
        PermissionHelper::create($group, ['create', 'read', 'update', 'delete', 'approval'], $group);
        $this->output->writeln('<info>updated checklist permission</info>');

        $group = 'HISTORY';
        PermissionHelper::create($group, ['create', 'read', 'update', 'delete', 'approval'], $group);
        $this->output->writeln('<info>updated history permission</info>');

        $group = 'PTPP';
        PermissionHelper::create('PTPP FORM', ['create', 'read', 'update', 'delete', 'approval', 'file'], $group);
        PermissionHelper::create('PTPP FOLLOW UP', ['create', 'read', 'update', 'delete', 'approval', 'file'], $group);
        PermissionHelper::create('PTPP VERIFICATION', ['create', 'read', 'update', 'delete', 'approval'], $group);
        $this->output->writeln('<info>updated ptpp permission</info>');

        $group = 'CERTIFICATE';
        PermissionHelper::create($group, ['create', 'read', 'update', 'delete'], $group);
        $this->output->writeln('<info>updated certificate permission</info>');
        
        $group = 'SUBMISSION';
        PermissionHelper::create($group, ['menu', 'create', 'read', 'update', 'delete', 'approval', 'file'], $group);
        $this->output->writeln('<info>updated submission permission</info>');

        $group = 'USER';
        PermissionHelper::create($group, ['create', 'read', 'update', 'delete', 'permission'], $group);
        $this->output->writeln('<info>updated user permission</info>');
        
        $group = 'SETTING';
        PermissionHelper::create($group, ['menu', 'create', 'read', 'update', 'delete'], $group);
        $this->output->writeln('<info>updated setting permission</info>');

        $group = 'MASTER';
        PermissionHelper::create('MASTER PERIODE', ['create', 'read', 'update', 'delete'], $group);
        PermissionHelper::create('MASTER VENDOR', ['create', 'read', 'update', 'delete'], $group);
        PermissionHelper::create('MASTER ITEM', ['create', 'read', 'update', 'delete', 'copy'], $group);
        PermissionHelper::create('MASTER CATEGORY', ['create', 'read', 'update', 'delete'], $group);
        $this->output->writeln('<info>updated master permission</info>');
        
        $group = 'INVENTORY';
        PermissionHelper::create($group, ['create', 'read', 'update', 'delete'], $group);
        PermissionHelper::create($group . ' HISTORY', ['create', 'read', 'update', 'delete'], $group);
        $this->output->writeln('<info>updated inventory permission</info>');
        
    }
}
