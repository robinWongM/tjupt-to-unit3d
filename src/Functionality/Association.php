<?php

namespace robinWongM\TjuptToUnit3d\Functionality;

use ErrorException;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Torrent;
use App\Models\Forum;
use App\Models\Topic;
use App\Models\Post;
use App\Models\Category;
use App\Models\Group;
use App\Models\Permission;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Subtitle;

class Association
{
    /**
     *
     * @return int
     *
     * @throws ErrorException
     */
    public static function associateTable(): int
    {
        $results = 0;

        $results += self::associateTorrents();
        $results += self::associateComments();
        $results += self::associateForums();
        $results += self::associateTopicsAndPosts();
        self::reverseAssociateForums();
        $results += self::associateChatMessages();
        $results += self::associateSubtitles();

        return $results;
    }

    /**
     *
     * @return int
     *
     * @throws ErrorException
     */
    public static function associateTorrents(): int
    {
        $results = 0;

        echo 'All torrents: ' . Torrent::withAnyStatus()->count() . "\n";
        foreach (Torrent::withAnyStatus()->get() as $item) {
            if (!is_null($item->nexus_id)) {
                $item->user_id = User::where('nexus_id', $item->nexus_user_id)->first()->id;
                $item->category_id = Category::where('nexus_id', $item->nexus_category_id)->first()->id;
                $item->timestamps = false;
                $item->save();
                $results++;
            }
        }

        echo 'Processed ' . $results . ' torrents' . "\n";

        return $results;
    }

    /**
     *
     * @return int
     *
     * @throws ErrorException
     */
    public static function associateComments(): int
    {
        $results = 0;

        echo 'All comments: ' . Comment::count() . "\n";
        foreach (Comment::all() as $item) {
            if (!is_null($item->nexus_id)) {
                $item->user_id = User::where('nexus_id', $item->nexus_user_id)->first()->id;
                $item->torrent_id = Torrent::where('nexus_id', $item->nexus_torrent_id)->first()->id;
                $item->timestamps = false;
                $item->save();
                $results++;
            }
        }

        echo 'Processed ' . $results . ' comments' . "\n";

        return $results;
    }

    /**
     *
     * @return int
     *
     * @throws ErrorException
     */
    public static function associateForums(): int
    {
        $results = 0;

        echo 'All forums: ' . Forum::count() . "\n";
        foreach (Forum::all() as $item) {
            if (!is_null($item->nexus_id)) {
                if (!is_null($item->nexus_parent_id)) {
                    $item->parent_id = Forum::where('nexus_id', $item->nexus_parent_id)
                        ->whereNull('nexus_parent_id')->first()->id;
                    $item->timestamps = false;
                    $item->save();
                }
                $permissions = explode('$', $item->nexus_permission);
                self::createPermission($item->id, $permissions[0], $permissions[1], $permissions[2]);
                $results++;
            }
        }

        echo 'Processed ' . $results . ' forums' . "\n";

        return $results;
    }

    /**
     * @param int forum_id
     * @param int min_read
     * @param int min_write
     * @param int min_create
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function createPermission($forum_id, $min_read, $min_write, $min_create): void
    {
        Group::all()->map(function ($group) use ($forum_id, $min_read, $min_write, $min_create) {
            $per = new Permission();
            $per->forum_id = $forum_id;
            $per->group_id = $group->id;

            $per->show_forum = self::checkPermission($group->id, $min_read);
            $per->read_topic = self::checkPermission($group->id, $min_read);
            $per->reply_topic = self::checkPermission($group->id, $min_write);
            $per->start_topic = self::checkPermission($group->id, $min_create);

            $per->save();
        });
    }

    /**
     * @param int group_id
     * @param int min_class
     *
     * @return int
     */
    public static function checkPermission($group_id, $min_class): int
    {
        $groups = [
            0 => 15,
            1 => 3,
            2 => 11,
            3 => 12,
            4 => 13,
            5 => 14,
            6 => 16,
            7 => 16,
            8 => 16,
            9 => 16,
            10 => 3,
            11 => 3,
            12 => 7,
            13 => 6,
            14 => 4,
            15 => 4,
            16 => 10,
        ];

        $filtered = array_filter(
            $groups,
            function ($key) use ($min_class) {
                return $min_class <= $key;
            },
            ARRAY_FILTER_USE_KEY
        );

        return in_array($group_id, $filtered) ? 1 : 0;
    }

    /**
     *
     * @return int
     *
     * @throws ErrorException
     */
    public static function associateTopicsAndPosts(): int
    {
        $results = 0;

        echo 'All topics: ' . Topic::count() . "\n";
        foreach (Topic::all() as $item) {
            if (!is_null($item->nexus_parent_id)) {
                $posts = Post::where('nexus_parent_id', $item->nexus_id);
                $item->num_post = $posts->count();
                $item->last_reply_at = $posts->orderBy('created_at', 'DESC')->first()->created_at;
                $item->forum_id = Forum::where('nexus_id', $item->nexus_parent_id)
                    ->whereNotNull('nexus_parent_id')->first()->id;
                $item->timestamps = false;
                $item->save();
                $results++;
            }
        }

        echo 'All posts: ' . Post::count() . "\n";
        foreach (Post::all() as $item) {
            if (!is_null($item->nexus_parent_id)) {
                $item->user_id = User::where('nexus_id', $item->nexus_user_id)->first()->id;
                $item->topic_id = Topic::where('nexus_id', $item->nexus_parent_id)->first()->id;
                $item->timestamps = false;
                $item->save();
                $results++;
            }
        }

        echo 'Processed ' . $results . ' topics & posts' . "\n";

        foreach (Topic::all() as $item) {
            if (!is_null($item->nexus_parent_id)) {
                $posts = Post::where('nexus_parent_id', $item->nexus_id);
                $first_post = $posts->orderBy('created_at', 'asc')->first();
                $last_post = $posts->orderBy('created_at', 'desc')->first();
                $item->first_post_user_id = $first_post->user->id;
                $item->first_post_user_username = $first_post->user->username;
                $item->last_post_user_id = $last_post->user->id;
                $item->last_post_user_username = $last_post->user->username;
                $item->created_at = $first_post->created_at;
                $item->updated_at = $last_post->created_at;
                $item->timestamps = false;
                $item->save();
                $results++;
            }
        }

        return $results;
    }

    /**
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function reverseAssociateForums(): void
    {
        foreach (Forum::all() as $item) {
            if (!is_null($item->nexus_id) && !is_null($item->nexus_parent_id)) {
                $item->num_topic = $item->topics->count();
                $item->num_post = DB::table('topics')
                    ->where('forum_id', $item->id)
                    ->join('posts', 'topics.id', '=', 'posts.topic_id')
                    ->count();
                if ($item->num_post != 0) {
                    $last_topic = $item->topics()->orderBy('updated_at', 'desc')->first();
                    $item->last_topic_id = $last_topic->id;
                    $item->last_topic_name = $last_topic->name;
                    $item->last_topic_slug = $last_topic->slug;
                    $last_post_user_id = DB::table('topics')
                        ->where('forum_id', $item->id)
                        ->join('posts', 'topics.id', '=', 'posts.topic_id')
                        ->orderBy('posts.updated_at', 'desc')
                        ->first()->user_id;
                    $item->last_post_user_id = $last_post_user_id;
                    $item->last_post_user_username = DB::table('users')->find($last_post_user_id)->username;
                }
                $item->timestamps = false;
                $item->save();
            }
        }
    }

    /**
     *
     * @return int
     *
     * @throws ErrorException
     */
    public static function associateChatMessages(): int
    {
        $results = 0;

        echo 'All messages: ' . Message::count() . "\n";
        foreach (Message::whereNotNull('nexus_id')->get() as $item) {
            if ($item->nexus_user_id == 0) {
                $item->user_id = 2; // Bot
            } else {
                $user = User::where('nexus_id', $item->nexus_user_id)->first();
                if ($user) {
                    $item->user_id = $user->id;
                } else {
                    $item->user_id = 1;
                }
            }
            $item->timestamps = false;
            $item->save();
            $results++;
        }

        echo 'Processed ' . $results . ' messages' . "\n";

        return $results;
    }

    /**
     *
     * @return int
     *
     * @throws ErrorException
     */
    public static function associateSubtitles(): int
    {
        $results = 0;

        echo 'All subtitles: ' . Subtitle::count() . "\n";
        foreach (Subtitle::all() as $item) {
            $user = User::where('nexus_id', $item->nexus_user_id)->first();
            $torrent = Torrent::where('nexus_id', $item->nexus_torrent_id)->first();
            if ($user) {
                $item->user_id = $user->id;
            } else {
                $item->user_id = 1;
            }
            $item->torrent_id = $torrent->id;
            $item->timestamps = false;
            $item->save();
            $results++;
        }

        echo 'Processed ' . $results . ' subtitles' . "\n";

        return $results;
    }
}
