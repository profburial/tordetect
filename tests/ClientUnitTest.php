<?php namespace ProfBurial\TorDetect;

use Mockery as m;
use ProfBurial\TorDetect\Client;

// Mock php functions
function is_writable($file)
{
    return ClientUnitTest::$functions->is_writable($file);
}

function filemtime($file)
{
    return ClientUnitTest::$functions->filemtime($file);
}

function fopen($file, $mode)
{
    return ClientUnitTest::$functions->fopen($file, $mode);
}

function fwrite($file, $data)
{
    return ClientUnitTest::$functions->fwrite($file, $data);
}

function fclose($file)
{
    return ClientUnitTest::$functions->fclose($file);
}

function file_get_contents($url)
{
    return ClientUnitTest::$functions->file_get_contents($url);
}

class ClientUnitTest extends \PHPUnit_Framework_TestCase {

    public static $functions;

    public function setUp()
    {
        self::$functions = m::mock();
    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetSuccess()
    {
        // is_writable
        self::$functions
            ->shouldReceive('is_writable')
            ->once()
            ->andReturn(true);

        // filemtime
        self::$functions
            ->shouldReceive('filemtime')
            ->once()
            ->andReturn(1421021251);

        // fopen
        self::$functions
            ->shouldReceive('fopen')
            ->once()
            ->andReturn(true);

        // fwrite
        self::$functions
            ->shouldReceive('fwrite')
            ->once()
            ->andReturn(true);

        // fclose
        self::$functions
            ->shouldReceive('fclose')
            ->once()
            ->andReturn(true);

        // file_get_contents
        self::$functions
            ->shouldReceive('file_get_contents')
            ->twice()
            ->andReturn(
                "123.123.123.123\n234.234.234.234\n",
                '["123.123.123.123","234.234.234.234"]'
            );

        // Get ips
        $ips = (new Client(
            '/Users/profburial/data/data.json',
            24
        ))->get();

        $this->assertEquals($ips[0], "123.123.123.123");
        $this->assertEquals($ips[1], "234.234.234.234");
    }

    /**
     * @expectedException ProfBurial\TorDetect\FileNotWritten
     * @expectedExceptionMessage File could not be updated.
     */
    public function testGetUpdateFileNotWrittenFails()
    {
        // is_writable
        self::$functions
            ->shouldReceive('is_writable')
            ->once()
            ->andReturn(true);

        // filemtime
        self::$functions
            ->shouldReceive('filemtime')
            ->once()
            ->andReturn(1421021251);

        // fopen
        self::$functions
            ->shouldReceive('fopen')
            ->once()
            ->andReturn(true);

        // fwrite
        self::$functions
            ->shouldReceive('fwrite')
            ->once()
            ->andReturn(false);


        // file_get_contents
        self::$functions
            ->shouldReceive('file_get_contents')
            ->once()
            ->andReturn(
                "123.123.123.123\n234.234.234.234\n"
            );

        // Get ips
        $ips = (new Client(
            '/Users/profburial/data/data.json',
            24
        ))->get();
    }

    public function testGetNoUpdateSuccess()
    {
        // is_writable
        self::$functions
            ->shouldReceive('is_writable')
            ->once()
            ->andReturn(true);

        // filemtime
        self::$functions
            ->shouldReceive('filemtime')
            ->once()
            ->andReturn(1421021251);

        // file_get_contents
        self::$functions
            ->shouldReceive('file_get_contents')
            ->once()
            ->andReturn(
                '["123.123.123.123","234.234.234.234"]'
            );

        // Get ips
        $ips = (new Client(
            '/Users/profburial/data/data.json',
            999999999999999
        ))->get();

        $this->assertEquals($ips[0], "123.123.123.123");
        $this->assertEquals($ips[1], "234.234.234.234");
    }

    /**
     * @expectedException ProfBurial\TorDetect\InvalidFile
     * @expectedExceptionMessage Invalid File. Make sure it exists and is writable.
     */
    public function testGetCheckFileFailsNotWritable()
    {
        // is_writable
        self::$functions
            ->shouldReceive('is_writable')
            ->once()
            ->andReturn(false);

        // Get ips
        $ips = (new Client(
            '/Users/profburial/data/data.json',
            24
        ))->get();
    }

    public function testCheckIpMatchSuccess()
    {
        // is_writable
        self::$functions
            ->shouldReceive('is_writable')
            ->once()
            ->andReturn(true);

        // filemtime
        self::$functions
            ->shouldReceive('filemtime')
            ->once()
            ->andReturn(1421021251);

        // fopen
        self::$functions
            ->shouldReceive('fopen')
            ->once()
            ->andReturn(true);

        // fwrite
        self::$functions
            ->shouldReceive('fwrite')
            ->once()
            ->andReturn(true);

        // fclose
        self::$functions
            ->shouldReceive('fclose')
            ->once()
            ->andReturn(true);

        // file_get_contents
        self::$functions
            ->shouldReceive('file_get_contents')
            ->twice()
            ->andReturn(
                "123.123.123.123\n234.234.234.234\n",
                '["123.123.123.123","234.234.234.234"]'
            );

        // Check ip
        $check = (new Client(
            '/Users/profburial/data/data.json',
            24
        ))->check("123.123.123.123");

        $this->assertEquals($check, "123.123.123.123");
    }

    public function testCheckIpNoMatchSuccess()
    {
        // is_writable
        self::$functions
            ->shouldReceive('is_writable')
            ->once()
            ->andReturn(true);

        // filemtime
        self::$functions
            ->shouldReceive('filemtime')
            ->once()
            ->andReturn(1421021251);

        // fopen
        self::$functions
            ->shouldReceive('fopen')
            ->once()
            ->andReturn(true);

        // fwrite
        self::$functions
            ->shouldReceive('fwrite')
            ->once()
            ->andReturn(true);

        // fclose
        self::$functions
            ->shouldReceive('fclose')
            ->once()
            ->andReturn(true);

        // file_get_contents
        self::$functions
            ->shouldReceive('file_get_contents')
            ->twice()
            ->andReturn(
                "123.123.123.123\n234.234.234.234\n",
                '["123.123.123.123","234.234.234.234"]'
            );

        // Check ip
        $check = (new Client(
            '/Users/profburial/data/data.json',
            24
        ))->check("123.123.123.124");

        $this->assertEquals($check, false);
    }

    /**
     * @expectedException ProfBurial\TorDetect\InvalidIpAddress
     * @expectedExceptionMessage boobs is not a valid ip address.
     */
    public function testCheckIpValidIpFails()
    {
        // is_writable
        self::$functions
            ->shouldReceive('is_writable')
            ->once()
            ->andReturn(true);

        // filemtime
        self::$functions
            ->shouldReceive('filemtime')
            ->once()
            ->andReturn(1421021251);

        // fopen
        self::$functions
            ->shouldReceive('fopen')
            ->once()
            ->andReturn(true);

        // fwrite
        self::$functions
            ->shouldReceive('fwrite')
            ->once()
            ->andReturn(true);

        // fclose
        self::$functions
            ->shouldReceive('fclose')
            ->once()
            ->andReturn(true);

        // file_get_contents
        self::$functions
            ->shouldReceive('file_get_contents')
            ->twice()
            ->andReturn(
                "123.123.123.123\n234.234.234.234\n",
                '["123.123.123.123","234.234.234.234"]'
            );

        // Check ip
        $check = (new Client(
            '/Users/profburial/data/data.json',
            24
        ))->check("boobs");
    }
}