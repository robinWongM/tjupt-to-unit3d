<?php

namespace pxgamer\GazelleToUnit3d\Models;

use Illuminate\Database\Capsule\Manager;
use PHPUnit\Framework\TestCase;

class GazelleModelTest extends TestCase
{
    protected $capsule;

    public function setUp()
    {
        $this->capsule = new Manager();

        $this->capsule->addConnection([
            'driver'    => 'sqlite',
            'host'      => ':memory:',
            'database'  => 'gazelle',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);
    }

    public function testCanConstructGazelleModel()
    {
        $gazelle = new GazelleModel($this->capsule);

        $this->assertInstanceOf(GazelleModel::class, $gazelle);
    }
}
