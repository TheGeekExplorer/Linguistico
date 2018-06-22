<?php declare(strict_types=1); namespace Linguistico;

include_once("autoload.php");

use Linguistico\lib\Receive\OpenArtifact;
use Linguistico\lib\Send\BuildArtifact;
use Linguistico\lib\Exception;
use Linguistico\lib\Zip;



# If flag "h" then show help menu
try {
    if (isset($argv[1]) & (trim($argv[1]) == "h" or trim($argv[1]) == "help" or trim($argv[1]) == "-h" or trim($argv[1]) == "-help" or trim($argv[1]) == "--h" or trim($argv[1]) == "--help")) {
        echo "\n\n\n";
        echo "------------------------------------------\n";
        echo "  Linguistico - Create an interface file  \n";
        echo "------------------------------------------\n";
        echo "Param #1: Path and File to Directory to write to.\n";
        echo "Param #n: Value for interface file\n";
        echo "\n";
        echo "Example Usage:\n";
        echo "--------------\n\n";
        echo "  php linguistico-cli.php write \"PRODTRANS-CUSTOMER_00000000000000001\" \"1000110\" \"20180620\" \"103556\"\n";
        echo "..or..\n";
        echo "  php linguistico-cli.php read \"PRODTRANS-CUSTOMER_00000000000000001\"\n\n\n";
        die();
    }
} catch (\Exception $e) {
    Exception::cast("ERROR!", 500);
}


# Get the event details
try {

    # Get file to output to
    $file = $argv[1];

    # BuildArtifact the row
    for($i=2; $i<50; $i++) {
        if(!isset($argv[$i]))
            continue;
        $params[$i - 2] = trim($argv[$i]);
    }

} catch (\Exception $e) {
    Exception::cast("Error! Parameters not provided.", 500);
}


if ($argv[1] == "write") {

    # BuildArtifact row for interface file


} else if ($argv[1] == "read") {

    # Read an artifact
    $Artifact = new OpenArtifact();
    $res = $Artifact->ReadArtifact($params[0]);


    # Check if anything returned.
    if (!empty($res)) {

        # Chuck out a report to the console.
        echo "\n\n[LINGUISTICO REPORT] ~ ARTIFACT OK:\n";
        echo "> Hierarchy: " . count($res) . " Files\n";
        echo "> Manifest:  " . count($res['manifest']) . " Settings\n";
        echo "> Map:       " . count($res['map']) . " Fields\n";
        echo "> Data:      " . count($res['data']) . " Rows\n";
        echo "~ END OF ARTIFACT REPORT ~\n\n";

        #if(!empty($res))
        #    var_dump($res);

    # NOT OK... ERROR ON CLI:
    } else {

        # Chuck out a report to the console.
        echo "\n\n[LINGUISTICO REPORT] ~ ARTIFACT NOT OK!\n";
        echo "> Message: Could not output artifact as no content was returned!\n";
        echo "~ END OF ARTIFACT REPORT ~\n\n";

    }


}