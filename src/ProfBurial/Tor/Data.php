<?php namespace ProfBurial\Tor;

/**
 * ---------------------------
 * Class Data
 * @package ProfBurial\Tor
 * ---------------------------
 *
 * Manage tor exit node data
 *
 */

class Data {

    /**
     * CSV of tor exit nodes
     * @var string
     */
    private $url = 'https://torstatus.blutmagie.de/ip_list_exit.php/Tor_ip_list_EXIT.csv';

    /**
     * Check file and auto import data
     *
     * @param $file string
     * @param $hours int
     * @return bool
     * @throws InvalidFile
     */
    protected function checkFile($file, $hours)
    {
        // Check if file exists and is writable
        $this->writable($file);

        // Update if over x hours old
        if($this->update($file, (int) $hours))
        {
            $this->load($file);
        }

        return true;
    }

    /**
     * Get array of tor exit nodes
     *
     * @return string json
     */
    private function getIps()
    {
        return json_encode(array_filter(
            explode(PHP_EOL, file_get_contents($this->url))
        ));
    }

    /**
     * Check if file is writable
     *
     * @param $file string
     * @return bool
     * @throws InvalidFile
     */
    private function writable($file)
    {
        if(is_writable($file)) {
            return true;
        } else {
            throw new InvalidFile(
                "Invalid File: '". $file . "'. Make sure it exists and is writable.",
                400
            );
        }
    }

    /**
     * Check if file needs to be updated
     *
     * @param $file string
     * @param $hours int
     * @return bool
     */
    private function update($file, $hours)
    {
        // Get diff
        $diff = (new \DateTime())->diff((new \DateTime(date("Y-m-d H:i:s", filemtime($file)))));

        // Compare hours
        if((($diff->days * 24) + $diff->h) >= $hours)
        {
            return true;
        }

        return false;
    }

    /**
     * Load ip data
     *
     * @param $file
     * @return bool
     * @throws FileNotWritten
     */
    private function load($file)
    {
        //Write data to file
        $f = fopen($file, 'w+');
        if(!fwrite($f, $this->getIps()))
        {
            throw new FileNotWritten("'". $file ."' could not be updated.");
        }

        // Close file
        fclose($f);

        return true;
    }
}