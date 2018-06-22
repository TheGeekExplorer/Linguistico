<?php declare(strict_types=1); namespace Linguistico\lib\Receive;

use Linguistico\lib\Exception;
use Linguistico\lib\Utils;
use Linguistico\lib\Zip;


class ReadProperties extends Utils
{


    /**
     * Loads a manifest or map file into an object
     * @param string $data
     * @return mixed
     */
    public function LoadManifestFile (string $filename) {

        # Check file exists
        if (!file_exists($filename))
            Exception::cast("Could not locate manifest for artifact. $filename", 500);

        $data = file_get_contents($filename);

        # Split data into lines
        $data = explode("\r\n", $data);

        # Check that there are lines present
        if (sizeof($data) < 1 OR empty($data))
            Exception::cast("FAIL! The data in the artifact was null / not present. $filename", 500);

        # Put Key/Value pairs into variables
        foreach ($data as $line) {

            # If blank line ignore
            if (empty($line))
                continue;

            # Split line out to Key/Value pair
            list($key, $value) = explode("=", $line);

            # Put data into Manifest Object
            $manifest[$key] = $value;
        }

        return $manifest;
    }


    /**
     * Loads a manifest or map file into an object
     * @param string $filename
     * @param array $map
     * @return mixed
     */
    public function LoadDataFile (string $filename, array $map) {
        $data = file_get_contents($filename);

        # Split data into lines
        $data = explode("\r\n", $data);

        # Put Key/Value pairs into variables
        foreach ($data as $line) {

            # If line empty then skip
            if(empty($line) OR $line == "")
                continue;

            # Reset point to 0 (so beginning of line)
            $point = 0;

            # Split line up into it's mapped components
            foreach($map as $key => $length) {

                # Get the correct piece of text for the mapped item
                $record[$key] = trim(substr($line, $point, intval($length)));

                # Add length on to the point
                $point += $length;
            }

            # If record is empty then continue
            if(empty($record))
                Exception::cast("FAIL! The data line had content but the record was empty. $filename", 500);

            # Put data into Manifest Object
            $dataobject[] = $record;
        }

        if(empty($dataobject))
            Exception::cast("FAIL! Could not load data file for artifact! $filename", 500);

        return $dataobject;
    }

}