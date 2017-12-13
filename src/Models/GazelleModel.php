<?php

namespace pxgamer\GazelleToUnit3d\Models;

use Illuminate\Database\Capsule\Manager;

class GazelleModel
{
    protected $capsule;
    protected $tables = [];
    protected $columns = [];

    public function __construct(Manager $capsule)
    {
        $this->capsule = $capsule;
    }

    public function importAll()
    {
        //
    }
}
