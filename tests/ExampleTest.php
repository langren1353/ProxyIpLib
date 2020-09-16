<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $ip2region = new Ip2Region();

        $ip = '188.68.56.248';

        $info = $ip2region->btreeSearch($ip);

        echo var_export($info, true);

    }
}
