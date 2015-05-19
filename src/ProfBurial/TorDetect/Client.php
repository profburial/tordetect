<?php namespace ProfBurial\TorDetect;

/**
 * ----------------------------
 * Class Client
 * @package ProfBurial\Tor
 * ----------------------------
 *
 * Do stuff with your neat ass tor ips.
 *
 */

class Client extends Data {

    /**
     * File name for storing ip data
     * @var string
     */
    protected $filename;

    /**
     * @param $filename string
     * @param $hours int
     */
    public function __construct($filename, $hours)
    {
        if($this->checkFile($filename, (int) $hours)) {
            $this->filename = $filename;
        }
    }

    /**
     * Get ips
     *
     * @return mixed
     */
    public function get()
    {
        // Get ips
        return json_decode(file_get_contents($this->filename),true);
    }

    /**
     * Check if user is using tor
     *
     * @param $ip
     * @return bool
     * @throws InvalidIpAddress
     */
    public function check( $ip )
    {
        // Get ips
        $ips = $this->get();

        // Search
        $key = array_search($this->validateIp($ip), $ips);

        // Ip not found
        if($key === false)
        {
            return false;
        }

        // Ip found
        return $ips[$key];
    }

    /**
     * Validate ip address
     *
     * @param $ip
     * @return mixed
     * @throws InvalidIpAddress
     */
    protected function validateIp( $ip )
    {
        if(!filter_var($ip, FILTER_VALIDATE_IP))
        {
            throw new InvalidIpAddress($ip . ' is not a valid ip address.');
        }

        return $ip;
    }

}