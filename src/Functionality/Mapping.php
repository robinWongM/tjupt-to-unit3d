<?php

namespace robinWongM\TjuptToUnit3d\Functionality;

use stdClass;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Mapping
{
    /**
     * @param  string  $type
     * @param  stdClass  $data
     * @return array
     */
    public static function map(string $type, stdClass $data): array
    {
        return self::{'map'.$type}($data);
    }

    // TODO
    // 1. import class
    // 2. Custom CSS

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapUser(stdClass $data): array
    {
        // Original UNIT3D Groups
        // 1 ~ 6
        // Validating_4_0, Guest_3_10, User_6_30, Administrator_18_5000, Banned_1_0, Moderator_17_2500,
        // 7 ~ 10
        // Uploader_15_250, Trustee_16_1000, Bot_20_0, Owner_19_9999,
        // 11 ~ 15
        // Power User_7_40, Super User_8_50, Extreme User_9_60, Insane User_10_70, Leech_5_20,
        // 16 ~ 19
        // Veteran User_11_100, Seeder_12_80, Archivist_13_90, Internal_14_500,
        // 20 ~ 21
        // Disabled_2_0, Pruned_0_0

        // Incompatible UNIT3D Groups
        // Seeder Seedsize >= 5TB and account 1 month old and seedtime average 30 days or better
        // Archivist Seedsize >= 10TB and account 3 month old and seedtime average 60 days or better

        // Mapping Table

        // 0 Peasant       -> Leech 15
        // 1 User          -> User 3
        // 2 Power User    -> Power User 11, PowerUser >= 1TB and account 1 month old
        // 3 Elite User    -> Super User 12, SuperUser >= 5TB and account 2 month old
        // 4 Crazy User    -> Extreme User 13, ExtremeUser >= 20TB and account 3 month old
        // 5 Insane User   -> Insane User 14, InsaneUser >= 50TB and account 6 month old
        // 6 Veteran User  -> Veteran User 16, Veteran >= 100TB and account 1 year old
        // 7 Extreme User  -> Nothing
        // 8 Ultimate User -> Nothing
        // 9 Nexus Master  -> Nothing

        // 10 VIP
        // 11 Retiree
        // 12 Uploader      -> Uploader 7
        // 13 Moderator     -> Moderator 6
        // 14 Administrator -> Administrator 4
        // 15 SYSOP         -> Administrator 4
        // 16 Staff Leader  -> Owner 10
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

        return [
            'nexus_id' => $data->id,
            'username' => $data->username,
            'password' => ('$nexus$' . $data->secret . '$' . $data->passhash),
            'passkey' => $data->passkey ?? null,
            'group_id' => $groups[$data->class] ?? 3,
            'email' => $data->email ?? null,
            'uploaded' => $data->uploaded ?? 0,
            'downloaded' => $data->downloaded ?? 0,
            'image' => $data->avatar ?? null,
            'about' => $data->info ?? null,
            'signature' => $data->signature ?? null,
            'title' => $data->title ?? null,
            'active' => ($data->enabled == 'yes') ?? true,
            'hidden' => true,
            'invites' => $data->invites ?? 0,
            'created_at' => Carbon::createFromTimeString($data->added != '0000-00-00 00:00:00' ? $data->added : '0000-01-01 00:00:00'),
            'last_login' => Carbon::createFromTimeString($data->last_login != '0000-00-00 00:00:00' ? $data->last_login : '0000-01-01 00:00:00'),
            'rsskey' => md5(uniqid().time()),
            'api_token' => Str::random(100),
            'seedbonus' => $data->seedbonus,
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapCategory(stdClass $data): array
    {
        return [
            'nexus_id' => $data->id,
            'name' => $data->name,
            'slug' => $data->class_name,
            'position' => 0, // manually set default value
            'no_meta' => 1,
        ];
    }

    // TODO
    // 2. free & upload
    //    sp_state list:
    //      2 -> free
    //      3 -> 2x
    //      4 -> 2xfree
    //      5 -> 50%
    //      6 -> 2x 50%
    //      7 -> 30%

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapTorrent(stdClass $data): array
    {
        return [
            'nexus_id' => $data->id,
            'info_hash' => unpack('H*', $data->info_hash)[1] ?? null,
            'name' => $data->name ?? 0,
            'file_name' => $data->filename ?? null,
            'size' => $data->size ?? 0,
            'nfo' => $data->nfo ?? null,
            'imdb' => $data->url ?? null,
            'nexus_user_id' => $data->owner ?? null,
            'announce' => config('app.url') . '/announce/PID',
            'description' => $data->descr ?? null,
            'num_file' => $data->numfiles ?? 1,
            'seeders' => 0,
            'leechers' => 0,
            'times_completed' => $data->times_completed ?? 0,
            'free' => ($data->sp_state == 2 || $data->sp_state == 4) ? 1 : 0,
            'doubleup' => ($data->sp_state == 3 || $data->sp_state == 4 || $data->sp_state == 6) ? 1 : 0,
            'created_at' => Carbon::createFromTimeString($data->added),
            // 以创建时间为最后修改时间；NexusPHP 的 last_action 是最后动向，不是最后修改
            'updated_at' => Carbon::createFromTimeString($data->added),
            'subhead' => $data->small_descr,
            'slug' => 'nexus-imported',
            'user_id' => 0, // default value
            'nexus_category_id' => $data->category,
            'anon' => $data->anonymous == 'yes' ? 1 : 0,
            'type' => '',
            // Auto moderate
            'status' => 1,
            'moderated_at' => Carbon::createFromTimeString($data->added),
            'moderated_by' => 1,
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapComment(stdClass $data): array
    {
        return [
            'nexus_id' => $data->id,
            'content' => $data->text,
            'nexus_torrent_id' => $data->torrent,
            'nexus_user_id' => $data->user,
            'created_at' => $data->added,
            'updated_at' => Carbon::createFromTimeString($data->editdate != '0000-00-00 00:00:00' ? $data->editdate : $data->added)
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapForumCategory(stdClass $data): array
    {
        return [
            'nexus_id' => $data->id,
            'position' => $data->sort,
            'name' => $data->name,
            'description' => $data->description,
            'parent_id' => 0,
            'nexus_permission' => $data->minclassview . '$114514$114514',
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapForum(stdClass $data): array
    {
        return [
            'nexus_id' => $data->id,
            'position' => $data->sort,
            'name' => $data->name,
            'description' => $data->description,
            'nexus_parent_id' => $data->forid,
            'nexus_permission' => $data->minclassread . '$' . $data->minclasswrite . '$' . $data->minclasscreate,
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapTopic(stdClass $data): array
    {
        return [
            'nexus_id' => $data->id,
            'name' => $data->subject,
            'slug' => 'nexus-imported', // slug 好像没什么用，但又得 not NULL
            'state' => 'open',
            'num_post' => 0, // 需要手动填充这个数字
            'first_post_user_id' => 0, // 下同
            'last_post_user_id' => 0,
            'first_post_user_username' => '',
            'last_post_user_username' => '',
            // 'last_reply_at' => Carbon::createFromTimeString('0000-01-01 00:00:00'),
            'views' => $data->views,
            // created_at
            // updated_at
            'nexus_user_id' => $data->userid,
            'nexus_parent_id' => $data->forumid,
            'forum_id' => $data->forumid,
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapPost(stdClass $data): array
    {
        return [
            'nexus_id' => $data->id,
            'content' => $data->body,
            'created_at' => Carbon::createFromTimeString($data->added),
            'updated_at' => Carbon::createFromTimeString($data->editdate != '0000-00-00 00:00:00' ? $data->editdate : $data->added),
            'nexus_user_id' => $data->userid,
            'nexus_parent_id' => $data->topicid,
            'user_id' => 0,
            'topic_id' => 0,
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapChatMessage(stdClass $data): array
    {
        return [
            'nexus_id' => $data->id,
            'user_id' => 1,
            'chatroom_id' => 1,
            'message' => $data->text,
            'created_at' => Carbon::createFromTimestamp($data->date),
            'updated_at' => Carbon::createFromTimestamp($data->date),
            'nexus_user_id' => $data->userid,
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapSubtitle(stdClass $data): array
    {
        return [
            'id' => $data->id,
            'title' => $data->title,
            'file_name' => $data->filename,
            'ext' => $data->ext,
            'anonymous' => $data->anonymous,
            'hits' => $data->hits,
            'size' => $data->size,
            // Auto moderate
            'status' => 1,
            'moderated_at' => Carbon::createFromTimeString($data->added),
            'moderated_by' => 1,
            'created_at' => Carbon::createFromTimestamp($data->added),
            'updated_at' => Carbon::createFromTimestamp($data->added),
            'nexus_torrent_id' => $data->torrent_id,
            'nexus_user_id' => $data->uppedby,
        ];
    }
}
