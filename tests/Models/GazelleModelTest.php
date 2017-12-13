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

        $this->capsule->addConnection(
            [
                'driver'    => env('DB_CONNECTION', 'mysql'),
                'host'      => env('DB_HOST', '127.0.0.1'),
                'port'      => env('DB_PORT', 3306),
                'database'  => env('DB_DATABASE', 'unit3d'),
                'username'  => env('DB_USERNAME', 'root'),
                'password'  => env('DB_PASSWORD', 'root'),
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ]
        );

        $this->capsule->addConnection(
            [
                'driver'    => env('GAZELLE_DB_CONNECTION', 'mysql'),
                'host'      => env('GAZELLE_DB_HOST', '127.0.0.1'),
                'port'      => env('GAZELLE_DB_PORT', 3306),
                'database'  => env('GAZELLE_DB_DATABASE', 'gazelle'),
                'username'  => env('GAZELLE_DB_USERNAME', 'root'),
                'password'  => env('GAZELLE_DB_PASSWORD', 'root'),
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
            ],
            'gazelle'
        );
    }

    public function testCanConstructGazelleModel()
    {
        $gazelle = new GazelleModel($this->capsule);

        $this->assertInstanceOf(GazelleModel::class, $gazelle);
    }

    public function testCanImportAllUser()
    {
        $gazelle = new User($this->capsule);
        $gazelle->importAll();
    }

    public function testCanImportAllUserInfo()
    {
        $gazelle = new UserInfo($this->capsule);
        $gazelle->importAll();
    }

    public function testCanImportAllTorrent()
    {
        $gazelle = new Torrent($this->capsule);
        $gazelle->importAll();
    }
}
