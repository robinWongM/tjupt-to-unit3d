<?php

namespace pxgamer\GazelleToUnit3d\Models;

class Torrent extends GazelleModel
{
    protected $tables = [
        'gazelle' => 'torrents',
        'unit3d'  => 'torrents',
    ];

    protected $columns = [
        'UserID'      => 'user_id',
        'info_hash'   => 'info_hash',
        'FileCount'   => 'num_file',
        'Size'        => 'size',
        'Leechers'    => 'leechers',
        'Seeders'     => 'seeders',
        'Description' => 'description',
    ];
}
