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
            'password'   => $data->PassHash,
            'passkey'    => $data->Secret,
            'group_id'   => $data->Class,
            'email'      => $data->Email,
            'uploaded'   => $data->Uploaded,
            'downloaded' => $data->Downloaded,
            'image'      => $data->Avatar,
            'about'      => $data->Info,
            'title'      => $data->Title,
            'active'     => $data->Enabled,
            'hidden'     => $data->Visible,
            'invites'    => $data->Invites,
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
            'info_hash'   => unpack('H*', $data->info_hash)[0],
            'name'        => $data->filename,
            'size'        => $data->Size,
            'announce'    => $data->announce_url,
            'description' => $data->Description,
            'num_file'    => $data->FileCount,
            'seeders'     => $data->Seeders,
            'leechers'    => $data->Leechers,
            'created_at'  => Carbon::createFromTimestamp(strtotime($data->data)),
            'updated_at'  => Carbon::createFromTimestamp(strtotime($data->lastupdate)),
        ];
    }
}
