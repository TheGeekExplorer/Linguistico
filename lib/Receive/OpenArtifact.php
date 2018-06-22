<?php declare(strict_types=1); namespace Linguistico\lib\Receive;

use Linguistico\lib\Exception;
use Linguistico\lib\Receive\ReadProperties;
use Linguistico\lib\Utils;
use Linguistico\lib\Zip;


class OpenArtifact extends Utils
{

    /**
     * Finds all artifacts in a given directory path, then
     * returns their filenames in an array
     * @param string $path
     * @return array
     */
    public function FindArtifacts (string $path) {
        $artifacts=[];
        $files = dir($path);

        # run through files in directory path provided
        while (false !== ($file = $files->read())) {

            # If file contains ".zip" then it's an artifact
            if(substr($file,-4) == '.zip') {

                # Get the date/time the artifact was created
                $created = filectime($path . $file);

                # Append it to the artifacts array
                $artifacts[] = [
                    'filename'     => $file,
                    'creationDate' => $created
                ];
            }
        }

        # Sort the array by date/time ascending so artifacts will be loaded in correct
        # order if working down the array list of files.
        usort($artifacts,'Linguistico\lib\Receive\cmp_by_creationDate');

        # Return the list of artifacts
        return $artifacts;
    }


    /**
     * Reads in the Artifact and returns the contents in an object, along
     * with all of the headers and maps.
     * @param string $filename
     * @return mixed
     * @throws \Exception
     */
    public function ReadArtifact (string $filename) {

        # Chop .imp from end to give ArtifactID
        $artifactid = substr($filename, 0, -4);

        # Unpack Artifact
        $res = $this->Unpack("import/$filename");

        # Sleep so no clashes
        #sleep(3);

        if (!$res)
            throw new \Exception("Could not unpack specified artifact! $artifactid");

        # Set artifact object headers
        $ReadArtifact = new ReadProperties();
        $artifact['manifest'] = $ReadArtifact->LoadManifestFile("import/$artifactid/manifest.dat");
        $artifact['map']      = $ReadArtifact->LoadManifestFile("import/$artifactid/" . $artifact['manifest']['map']);
        $artifact['data']     = $ReadArtifact->LoadDataFile(
            "import/$artifactid/" . $artifact['manifest']['data'],
            $artifact['map']
        );

        # Return the Artifact
        return $artifact;
    }


    /**
     * Unpacks the chosen Artifact
     * @param string $filename
     * @return bool
     */
    private function Unpack (string $filename)
    {
        $outputdir = "import/";

        # Create artifact directory in import
        #mkdir("import/$artifactid");
        #chmod("import/$artifactid", "0600");

        $zip = new Zip($filename);
        $res = $zip->Unzip($outputdir);

        return $res;
    }


}


/**
 * Sorts the array of artifacts by creation date in
 * order of ascending
 * @param $a
 * @param $b
 * @return mixed
 */
function cmp_by_creationDate($a, $b) {
    return $a["creationDate"] - $b["creationDate"];
}