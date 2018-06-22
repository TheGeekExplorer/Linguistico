<?php declare(strict_types=1); namespace Linguistico\lib;

use Linguistico\lib\Exception;


class Zip
{
    public $ZIP;
    public $file;

    /**
     * Zip constructor.
     * @param string $file
     */
    public function __construct (string $file) {
        $this->file = $file;
        $this->ZIP = new \ZipArchive();
    }

    /**
     * Unzips an artifact file
     * @param string $artifactid
     * @param string $filename
     * @param string $outputdir
     * @return bool
     */
    public function Unzip (string $outputdir) {

        # Extract the artifact
        $res = $this->ZIP->open($this->file);

        # If OK then extract, of not then error
        if ($res === TRUE) {

            # Extract artifact
            $this->ZIP->extractTo($outputdir);
            $this->ZIP->close();
            return true;

        } else {
            $this->ZIP->close();
            return false;
        }
    }


    /**
     * Creates the Archive from the instantiated filename
     * @return mixed
     */
    public function create () {
        # Opens new archive file in Create Mode
        if (!$this->ZIP->open($this->file, \ZipArchive::CREATE))
            Exception::cast("FAIL! Could not create archive!", 500);

        # Adds a simple text file so Zip file is created (required)
        if (!$this->ZIP->addFromString('README.txt','Meh.'))
            Exception::cast("FAIL! Could not create test file within archive. Archive will not be created. Exit.", 500);

        # Closes the archive for completeness.
        if (!$this->ZIP->close())
            Exception::cast("FAIL! Could not close Archive.", 500);
        return true;
    }


    /**
     * Creates a child directory within the chosen archive
     * @param string $dirname
     * @return bool
     */
    public function addDirectory (string $dirname) {
        # Opens the archive
        if(!$this->ZIP->open($this->file))
            Exception::cast("FAIL! Could not open specified archive.", 500);

        # Adds the directory to the archive
        if(!$this->ZIP->addEmptyDir($dirname))
            Exception::cast("FAIL! Could not add empty child directory to chosen archive.", 500);

        # Closes the archive for completeness.
        if (!$this->ZIP->close())
            Exception::cast("FAIL! Could not close Archive.", 500);
        return true;
    }


    /**
     * Creates a child text file within the archive
     * @param string $filename
     * @param string $text
     * @return bool
     */
    public function addTextFile (string $filename, string $text) {
        # Opens the archive
        if(!$this->ZIP->open($this->file))
            Exception::cast("FAIL! Could not open specified archive.", 500);

        # Adds the directory to the archive
        if(!$this->ZIP->addFromString($filename, $text))
            Exception::cast("FAIL! Could not create text file within archive.", 500);

        # Closes the archive for completeness.
        if (!$this->ZIP->close())
            Exception::cast("FAIL! Could not close Archive.", 500);
        return true;
    }
}