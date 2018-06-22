<?php declare(strict_types=1); namespace Linguistico\lib\Send;

use Linguistico\lib\Exception;
use Linguistico\lib\Send\WriteProperties;
use Linguistico\lib\Utils;
use Linguistico\lib\Zip;


class BuildArtifact extends Utils {

    public $artifactId;
    public $map;
    public $mapName;
    public $mapText;
    public $manifest;
    public $manifestText;
    public $data;
    public $dataText;

    /**
     * BuildArtifact constructor.
     * @param string $mapName
     */
    public function __construct (string $mapName) {
        $WP = new WriteProperties();

        # Set headers
        $this->artifactId   = sha1(strval(time())); # Creates SHA1 from: Epoch Time converted into text
        $this->mapName      = $mapName;
        $this->map          = $WP->readMap($this->mapName, 'array');
        $this->mapText      = $WP->readMap($this->mapName, 'text');
        $this->manifest     = $WP->createManifest($this->artifactId, $this->mapName, 'array');
        $this->manifestText = $WP->createManifest($this->artifactId, $this->mapName, 'text');
    }


    /**
     * COMMITS RECORDS to ARTIFACT and BUILDS ZIP
     * @return bool
     */
    public function build () : bool {
        $ZIP = new Zip('export/' . $this->artifactId . '.zip');

        # Create ZIP File
        $ZIP->create();
        $ZIP->addDirectory($this->artifactId);

        # Write Map, and add to ZIP
        $ZIP->addTextFile(
            $this->artifactId . '/' . $this->mapName . '.map',
            $this->mapText
        );

        # Write Manifest, and add to ZIP
        $ZIP->addTextFile(
            $this->artifactId . '/' . 'manifest.dat',
            $this->manifestText
        );

        # Write Data File, and add to ZIP
        $ZIP->addTextFile(
            $this->artifactId . '/' . $this->mapName . '.imp',
            $this->data
        );

        return true;
    }


    /**
     * ADD RECORD to ARTIFACT
     * @param array $params
     * @return bool
     * @throws \Exception
     */
    public function addRecord (array $params) : bool {
        $row='';

        # If param count doesn't match definition then throw error
        if (sizeof($this->map) != sizeof($params))
            throw new \Exception("Provided params count doesn't match definition map for interface! Exited.");

        # BuildArtifact the row
        $leng = sizeof($params);
        foreach ($params as $key => $value) {
            $value = $this->generateBlankChars($value, intval($this->map[$key]));
            $row .= $value;
        }

        # If row empty then error
        if (empty($row))
            Exception::cast("FAIL! The row was empty.  Did you provide the right fields in?", 500);

        # Append the record to the data
        $this->data .= "$row\r\n";

        # Return row to log into file
        return true;
    }
    

    
    
}
