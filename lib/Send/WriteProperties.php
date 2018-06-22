<?php declare(strict_types=1); namespace Linguistico\lib\Send;

use Linguistico\lib\Exception;
use Linguistico\lib\Utils;


class WriteProperties extends Utils
{
    /**
     * Creates a manifest file structure
     * @param string $artifactId
     * @param string $mapName
     * @return string
     */
    public function createManifest (string $artifactId, string $mapName, string $mode) {
        # If text mode then return a static file format, else an array
        if ($mode == 'text') {
            $manifest = "id=$artifactId\r\n";
            $manifest .= "date=" . date("Ymd") . "\r\n";
            $manifest .= "time=" . gmdate("his") . "\r\n";
            $manifest .= "map=$mapName.map\r\n";
            $manifest .= "data=$mapName.imp\r\n";
        }
        else
        {
            # Array format
            $manifest['id']   = $artifactId;
            $manifest['date'] = date("Ymd");
            $manifest['time'] = gmdate("his");
            $manifest['map']  = $mapName . '.map';
            $manifest['data'] = $mapName . '.imp';
        }
        return $manifest;
    }


    /**
     * Reads a map from the maps directory and then returns the contents
     * @param string $mapName
     * @param string $mode
     * @return string
     */
    public function readMap (string $mapName, string $mode) {
        if (!file_exists('maps/' . $mapName . '.map'))
            Exception::cast("FAIL! Could not read specified map.", 500);

        # Read file contents into variable
        $res = file_get_contents('maps/' . $mapName . '.map');

        # Check for content
        if (empty($res))
            Exception::cast("FAIL! Content of map was empty.", 500);

        # If mode is text then return text of file
        if ($mode == 'text')
            return $res;

        # Turn map into array object
        $lines = explode("\r\n", $res);
        foreach ($lines as $line) {
            list($key, $value) = explode("=", $line);
            $mapObj[$key] = $value;
        }
        # Return to requester
        return $mapObj;
    }


    /**
     * Creates a Map file structure
     * @param array $fields
     * @return string
     */
    public function createMap (array $fields) : string {
        $map = "";
        foreach($fields as $key => $value) {
            $map .= "$key=$value\r\n";
        }
        return $map;
    }


}