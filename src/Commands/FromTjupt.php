<?php

namespace robinWongM\TjuptToUnit3d\Commands;

use ErrorException;
use App\Models\User;
use App\Models\Torrent;
use App\Models\Forum;
use App\Models\Topic;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Subtitle;
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
                            {--ignore-categories : Ignore the categories table when importing}
                            {--ignore-torrents : Ignore the torrents table when importing}
                            {--disable-nfo : Ignore the nfo of torrents when importing}
                            {--ignore-forums : Ignore the forum-related tables when importing}
                            {--ignore-topics : Ignore the topics tables when importing}
                            {--ignore-posts : Ignore the posts tables when importing}
                            {--ignore-chat-messages : Ignore the chat messages tables when importing}
                            {--ignore-subtitles : Ignore the subtitles tables when importing}';

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
        $this->importAvatars($database);
        $this->importCategories($database);
        $this->importTorrents($database);
        $this->importForumCategories($database);
        $this->importForums($database);
        $this->importTopics($database);
        $this->importPosts($database);
        $this->importChatMessages($database);
        $this->importSubtitles($database);
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

        Imports::importTable($database, 'User', 'users', User::class, true);
        echo "User imported.\n";
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importCategories(ConnectionInterface $database): void
    {
        if ($this->option('ignore-categories')) {
            $this->output->note('Ignoring categories table');

            return;
        }

        Imports::importTable($database, 'Category', 'categories', Category::class, false);
        echo "Categories imported.\n";
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

        Imports::importTable($database, 'Torrent', 'torrents', Torrent::class, false);
        Imports::importTable($database, 'Comment', 'comments', Comment::class, false);
        echo "Torrents & comments imported.\n";
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importChatMessages(ConnectionInterface $database): void
    {
        if ($this->option('ignore-chat-messages')) {
            $this->output->note('Ignoring chat messages table');

            return;
        }

        Imports::importTable($database, 'ChatMessage', 'shoutbox', Message::class, false);
        echo "Chat messages imported.\n";
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importForumCategories(ConnectionInterface $database): void
    {
        if ($this->option('ignore-forums')) {
            $this->output->note('Ignoring forum categories table');

            return;
        }

        Imports::importTable($database, 'ForumCategory', 'overforums', Forum::class, true);
        echo "ForumCategories imported.\n";
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importForums(ConnectionInterface $database): void
    {
        if ($this->option('ignore-forums')) {
            $this->output->note('Ignoring forum table');

            return;
        }

        Imports::importTable($database, 'Forum', 'forums', Forum::class, true);
        echo "Forums imported.\n";
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importTopics(ConnectionInterface $database): void
    {
        if ($this->option('ignore-topics')) {
            $this->output->note('Ignoring topics table');

            return;
        }

        Imports::importTable($database, 'Topic', 'topics', Topic::class, false);
        echo "Topics imported.\n";
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importPosts(ConnectionInterface $database): void
    {
        if ($this->option('ignore-posts')) {
            $this->output->note('Ignoring posts table');

            return;
        }

        Imports::importTable($database, 'Post', 'posts', Post::class, false);
        echo "Posts imported.\n";
    }

    /**
     * @param  ConnectionInterface  $database
     *
     * @throws ErrorException
     */
    private function importSubtitles(ConnectionInterface $database): void
    {
        if ($this->option('ignore-subtitles')) {
            $this->output->note('Ignoring subtitles table');

            return;
        }

        Imports::importTable($database, 'Subtitle', 'subs', Subtitle::class, false);
        echo "Subtitles imported.\n";
    }

    private function importAvatars(ConnectionInterface $database): void
    {
        $oldData = $database->query()->select()->from('users')->get();

        foreach ($oldData->all() as $oldDataItem) {
            $avatarUrl = $oldDataItem->avatar;
            if($avatarUrl) {
                $avatar = file_get_contents($avatarUrl);
                $urlPath = parse_url($avatarUrl, PHP_URL_PATH);
                $ext = pathinfo($urlPath, PATHINFO_EXTENSION);
                $path = public_path('/files/img/' . $oldDataItem->username . '.' . $ext);
                $ret = file_put_contents($path, $avatar);
                if ($ret) {
                    echo 'Download avatar of ' . $oldDataItem->username . ' successfully';
                } else {
                    echo 'Download avatar of ' . $oldDataItem->username . ' failed';
                }
            }
        }
    }
}
