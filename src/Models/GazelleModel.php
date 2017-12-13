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
        $gazelleConnection = $this->capsule->getConnection('gazelle');
        $all = $gazelleConnection
            ->table($this->tables['gazelle'])
            ->get(array_keys($this->columns));

        foreach ($all as $row) {
            $this->processRow($row);
        }
    }

    protected function processRow(\stdClass $row)
    {
    }
}
