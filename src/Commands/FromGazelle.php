<?php

namespace pxgamer\GazelleToUnit3d\Commands;

use App\User;
use App\Torrent;
use ErrorException;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use pxgamer\GazelleToUnit3d\Functionality\Imports;

class FromGazelle extends Command
{
    /** @var string The name and signature of the console command */
    protected $signature = 'unit3d:from-gazelle
                            {--driver=mysql : The driver type to use (mysql, sqlsrv, etc.)}
                            {--host=localhost : The hostname or IP}
                            {--database= : The database to select from}
                            {--username= : The database user}
                            {--password= : The database password}
                            {--prefix= : The database hostname or IP}
                            {--ignore-users : Ignore the users table when importing}
                            {--ignore-torrents : Ignore the torrents table when importing}';

    /** @var string The console command description */
    protected $description = 'Import data from a Gazelle instance to UNIT3D';

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws ErrorException
     */
    public function handle(): void
    {
        $this->checkRequired($this->options());

        config([
            'database.connections.imports' => [
                'driver' => $this->option('driver'),
                'host' => $this->option('host'),
                'database' => $this->option('database'),
                'username' => $this->option('username'),
                'password' => $this->option('password'),
                'prefix' => $this->option('prefix'),
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
        ]);

        $database = DB::connection('imports');

        if (! $this->option('ignore-users')) {
            Imports::importTable($database, 'User', 'users_main', User::class);
        } else {
            $this->output->note('Ignoring users table');
        }

        if (! $this->option('ignore-torrents')) {
            Imports::importTable($database, 'Torrent', 'torrents', Torrent::class);
        } else {
            $this->output->note('Ignoring torrents table');
        }
    }

    public function checkRequired(array $options): void
    {
        $requiredOptions = [
            'database',
            'username',
            'password',
        ];

        foreach ($requiredOptions as $option) {
            if (! array_key_exists($option, $options) || ! $options[$option]) {
                throw new InvalidArgumentException('Option `'.$option.'` not provided');
            }
        }
    }
}
