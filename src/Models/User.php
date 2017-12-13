<?php

namespace pxgamer\GazelleToUnit3d\Models;

class User extends GazelleModel
{
    protected $tables = [
        'gazelle' => 'users_main',
        'unit3d'  => 'users',
    ];

    protected $columns = [
        'Username'   => 'username',
        'Email'      => 'email',
        'PassHash'   => 'password',
        'Secret'     => 'passkey',
        'Enabled'    => 'active',
        'Class'      => 'group_id',
        'Uploaded'   => 'uploaded',
        'Downloaded' => 'downloaded',
        'Title'      => 'title',
        'Visible'    => 'hidden',
        'Invites'    => 'invites',
    ];
}
