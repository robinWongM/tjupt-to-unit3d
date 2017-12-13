<?php

namespace pxgamer\GazelleToUnit3d\Models;

class UserInfo extends GazelleModel
{
    protected $tables = [
        'gazelle' => 'users_info',
        'unit3d'  => 'users',
    ];

    protected $columns = [
        'Info'   => 'about',
        'Avatar' => 'image',
    ];
}
