<?php

use ProfBurial\TorDetect\Client;

class ClientIntegrationTest extends \PHPUnit_Framework_TestCase {

    public function setUp()
    {
        // Create file
        system('touch '. __DIR__ .'/_data/data.json');
        system('chmod 775 '. __DIR__ .'/_data/data.json');
    }

    public function testGetSuccess()
    {
        // Get ips
        $ips = (new Client(
            __DIR__.'/_data/data.json',
            0
        ))->get();

        $this->assertGreaterThan(1, count($ips));
        $this->assertRegExp('/\d+\.\d+\.\d+\.\d+/',$ips[0]);
    }

    public function testGetNoUpdateSuccess()
    {
        // Get ips
        $ips = (new Client(
            __DIR__.'/_data/data.json',
            999999999999999
        ))->get();

        $this->assertGreaterThan(1, count($ips));
        $this->assertRegExp('/\d+\.\d+\.\d+\.\d+/',$ips[0]);
    }

    /**
     * @expectedException ProfBurial\TorDetect\InvalidFile
     */
    public function testGetCheckFileFailsNotWritable()
    {
        system('chmod 400 '. __DIR__ .'/_data/data.json');

        // Get ips
        $ips = (new Client(
            __DIR__.'/_data/data.json',
            999999999999999
        ))->get();
    }

    public function testCheckIpNoMatchSuccess()
    {
        // Check ip
        $check = (new Client(
            __DIR__.'/_data/data.json',
            999999999999999
        ))->check("123.123.123.124");

        $this->assertEquals($check, false);
    }

    /**
     * @expectedException ProfBurial\TorDetect\InvalidIpAddress
     * @expectedExceptionMessage boobs is not a valid ip address.
     */
    public function testCheckIpValidIpFails()
    {
        // Check ip
        $check = (new Client(
            __DIR__.'/_data/data.json',
            999999999999999
        ))->check("boobs");
    }

}