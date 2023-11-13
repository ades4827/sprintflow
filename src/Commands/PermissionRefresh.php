<?php

namespace Ades4827\Sprintflow\Commands;

use Ades4827\Sprintflow\Controllers\PermissionController;
use Illuminate\Console\Command;

class PermissionRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate role/permission from PermissionController array';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $permission_controller = new PermissionController();
        $reports = $permission_controller->refreshDatabase();

        $this->info('Permessi rigenerati sulla base del PermissionController');

        foreach ($reports as $section_name => $report) {
            $this->table($report['headers'], $report['rows']);
        }
    }
}
