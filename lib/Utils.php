<?php declare(strict_types=1); namespace Linguistico\lib;

use Linguistico\lib\Exception;


class Utils
{

    /**
     * Generates the remaining blank spaces left in the field after the
     * input text has been accounted for (fixed length field)
     * @param int $fieldLimit
     * @param string $inputText
     * @return string */
    public function generateBlankChars (string $inputText, int $fieldLimit) : string {

        # Get the remaining characters left to the limit
        if($fieldLimit == 0)
            $fieldLimit = strlen($inputText);

        # Get chars remaining to fill fixed field
        $charsLeft = ($fieldLimit - strlen($inputText));

        #die("\n\n$charsLeft\n\n");


        # Generate the rest of the chars in the field
        $blankChars="";
        for ($i=0; $i<$charsLeft; $i++) {
            $blankChars .= " ";
        }

        # Return total field
        return $inputText . $blankChars;
    }
}