<?php

namespace pxgamer\GazelleToUnit3d\Functionality;

use Carbon\Carbon;

/**
 * Class Mapping
 */
class Mapping
{
    /**
     * @param string $type
     * @param \stdClass $data
     * @return array
     */
    public static function map(string $type, \stdClass $data): array
    {
        return self::{'map'.$type}($data);
    }

    /**
     * @param \stdClass $data
     * @return array
     */
    public static function mapUser(\stdClass $data): array
    {
        return [
            'username'   => $data->Username,
            'password'   => $data->PassHash ?? null,
            'passkey'    => $data->Secret ?? null,
            'group_id'   => $data->Class ?? 1,
            'email'      => $data->Email ?? null,
            'uploaded'   => $data->Uploaded ?? 0,
            'downloaded' => $data->Downloaded ?? 0,
            'image'      => $data->Avatar ?? null,
            'about'      => $data->Info ?? null,
            'title'      => $data->Title ?? null,
            'active'     => $data->Enabled ?? true,
            'hidden'     => $data->Visible ?? true,
            'invites'    => $data->Invites ?? 0,
            'created_at' => Carbon::createFromTimestamp(strtotime($data->joined)),
            'last_login' => Carbon::createFromTimestamp(strtotime($data->lastconnect)),
        ];
    }

    /**
     * @param \stdClass $data
     * @return array
     */
    public static function mapTorrent(\stdClass $data): array
    {
        return [
            'info_hash'   => unpack('H*', $data->info_hash)[1] ?? null,
            'name'        => $data->filename ?? 0,
            'size'        => $data->Size ?? 0,
            'announce'    => $data->announce_url ?? null,
            'description' => $data->Description ?? null,
            'num_file'    => $data->FileCount ?? 1,
            'seeders'     => $data->Seeders ?? 0,
            'leechers'    => $data->Leechers ?? 0,
            'created_at'  => Carbon::createFromTimestamp(strtotime($data->data)),
            'updated_at'  => Carbon::createFromTimestamp(strtotime($data->lastupdate)),
        ];
    }
}
