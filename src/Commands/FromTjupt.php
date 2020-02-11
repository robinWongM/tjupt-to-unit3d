<?php

namespace robinWongM\TjuptToUnit3d\Commands;

use ErrorException;
use App\Models\User;
use App\Models\Torrent;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\ConnectionInterface;
use robinWongM\TjuptToUnit3d\Functionality\Association;
use robinWongM\TjuptToUnit3d\Functionality\Imports;

class FromTjupt extends Command
{
    /** @var string The name and signature of the console command */
    protected $signature = 'unit3d:from-tjupt
                            {--driver=mysql : The driver type to use (mysql, sqlsrv, etc.)}
                            {--host=localhost : The hostname or IP}
                            {--database= : The database to select from}
                            {--username= : The database user}
                            {--password= : The database password}
                            {--prefix= : The database hostname or IP}
                            {--ignore-users : Ignore the users table when importing}
                            {--ignore-torrents : Ignore the torrents table when importing}
                            {--ignore-forums : Ignore the forum-related tables when importing}';

    /** @var string The console command description */
    protected $description = 'Import data from a Tjupt instance to UNIT3D';

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

        $this->importUsers($database);
        $this->importTorrents($database);
        $this->importForumCategories($database);
        $this->importForums($database);
        $this->importTopics($database);
        $this->importPosts($database);
        Association::associateTable();
    }

    private function checkRequired(array $options): void
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

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importUsers(ConnectionInterface $database): void
    {
        if ($this->option('ignore-users')) {
            $this->output->note('Ignoring users table');

            return;
        }

        Imports::importTable($database, 'User', 'users', User::class);
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importTorrents(ConnectionInterface $database): void
    {
        if ($this->option('ignore-torrents')) {
            $this->output->note('Ignoring torrents table');

            return;
        }

        Imports::importTable($database, 'Torrent', 'torrents', Torrent::class);
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importForumCategories(ConnectionInterface $database): void
    {
        if ($this->option('ignore-forum')) {
            $this->output->note('Ignoring forum categories table');

            return;
        }

        Imports::importTable($database, 'ForumCategory', 'overforums', Forum::class);
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importForums(ConnectionInterface $database): void
    {
        if ($this->option('ignore-forum')) {
            $this->output->note('Ignoring forum table');

            return;
        }

        Imports::importTable($database, 'Forum', 'forums', Forum::class);
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importTopics(ConnectionInterface $database): void
    {
        if ($this->option('ignore-forum')) {
            $this->output->note('Ignoring topics table');

            return;
        }

        Imports::importTable($database, 'Topic', 'topics', Torrent::class);
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importPosts(ConnectionInterface $database): void
    {
        if ($this->option('ignore-forum')) {
            $this->output->note('Ignoring posts table');

            return;
        }

        Imports::importTable($database, 'Post', 'posts', Post::class);
    }
}
