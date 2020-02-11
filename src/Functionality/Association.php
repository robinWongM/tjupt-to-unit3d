<?php

namespace robinWongM\TjuptToUnit3d\Functionality;

use ErrorException;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;

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

        foreach (Torrent::all() as $item) {
            if (!is_null($item->nexus_parent_id)) {
                $item->user_id = User::where('nexus_id', $item->nexus_parent_id)->first()->id;
                $results++;
            }
        }

        foreach (Forum::all() as $item) {
            if (!is_null($item->nexus_parent_id)) {
                $item->parent_id = Forum::where('nexus_id', $item->nexus_parent_id)->first()->id;
                $results++;
            }
        }

        foreach (Topic::all() as $item) {
            if (!is_null($item->nexus_parent_id)) {
                $posts = Post::where('nexus_parent_id', $item->nexus_id);
                $item->num_post = $posts->count();
                $item->last_reply_at = $posts->orderBy('created_at', 'DESC')->first()->created_at;
                $item->forum_id = Forum::where('nexus_id', $item->nexus_parent_id)->first()->id;
                $results++;
            }
        }

        foreach (Post::all() as $item) {
            if (!is_null($item->nexus_parent_id)) {
                $item->user_id = User::where('nexus_id', $item->nexus_user_id)->first()->id;
                $item->topic_id = Topic::where('nexus_id', $item->nexus_parent_id)->first()->id;
                $results++;
            }
        }

        foreach (Topic::all() as $item) {
            if (!is_null($item->nexus_parent_id)) {
                $posts = Post::where('nexus_parent_id', $item->nexus_id);
                $first_post = $posts->orderBy('created_at', 'asc')->first();
                $last_post = $posts->orderBy('created_at', 'desc')->first();
                $item->first_post_user_id = $first_post->user->id;
                $item->first_post_user_username = $first_post->user->username;
                $item->last_post_user_id = $last_post->user->id;
                $item->last_post_user_username = $last_post->user->username;
                $results++;
            }
        }

        return $results;
    }
}
