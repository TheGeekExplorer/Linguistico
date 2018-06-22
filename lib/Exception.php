<?php declare(strict_types=1); namespace Linguistico\lib;


class Exception {
    /**
     * Throws an exception and sets the response code to whatever you choose.
     * @param $message
     * @param $responseCode
     */
    final public static function cast ($message, $responseCode) {
        http_response_code($responseCode);
        header("Content-type: text/plain");
        die ("\n\nError $responseCode.\n\n$message\n\n");
    }
}
