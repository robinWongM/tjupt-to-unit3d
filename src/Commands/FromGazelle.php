<?php

namespace pxgamer\GazelleToUnit3d\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Capsule\Manager;
use pxgamer\GazelleToUnit3d\Models\User;
use pxgamer\GazelleToUnit3d\Models\UserInfo;

class FromGazelle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unit3d:from-gazelle
                            {--driver=mysql : The Gazelle driver type to use (mysql, sqlsrv, etc.).}
                            {--host=localhost : The Gazelle hostname or IP.}
                            {--database= : The Gazelle database to select from.}
                            {--username= : The Gazelle mysql user.}
                            {--password= : The Gazelle mysql password.}
                            {--prefix= : The Gazelle hostname or IP.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from a Gazelle instance to UNIT3D.';

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
     * @return mixed
     * @throws \ErrorException
     */
    public function handle()
    {
        $capsule = $this->getCapsule();

        if (!$capsule->schema()->hasTable('users_main') || !$capsule->schema()->hasTable('users_info')) {
            throw new \ErrorException('Gazelle user tables missing.');
        }

        $users = new User($capsule);
        $users->importAll();

        $userInfo = new UserInfo($capsule);
        $userInfo->importAll();
    }

    private function getCapsule()
    {
        $capsule = new Manager();

        $capsule->addConnection([
            'driver'    => $this->option('driver'),
            'host'      => $this->option('host'),
            'database'  => $this->option('database'),
            'username'  => $this->option('username'),
            'password'  => $this->option('password'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => $this->option('driver'),
        ]);

        return $capsule;
    }
}
