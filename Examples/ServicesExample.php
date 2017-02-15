<?php
/*
TODO: This description
This serves as an example entry point.
Designed to be run on the CLI.
*/

/* SERVER VARIABLES */
DEFINE('PATH_ROOT', realpath($_SERVER["DOCUMENT_ROOT"]) . DIRECTORY_SEPARATOR);

//Bring in the include/defines
require_once(PATH_ROOT . 'Include/master.inc.php');

//check that options are set
if(!isset($options)) {
    $options = getopt('', ["service:", "method:", "serviceArguments:", "jsonArgs:"]);
} else {
    throw new \Exception('Missing options!');
}

$_SESSION["internal"] = false;

//get the service handler class
$serviceHandlerClass = \Examples\Implementors\ServiceExample_Class::getInstance(["loggingDisabled" => true]);

//Pull in arguments
$argumentsArray["methodArguments"] = json_decode($options["jsonArgs"], true);
$argumentsArray["serviceArguments"] = json_decode($options["serviceArguments"], true);

//Check for the requested service and method
if ((@array_key_exists("service", $options)) && (@array_key_exists("method", $options))) {
    $argumentsArray["service"]= ucfirst($options["service"]);
    $argumentsArray["method"] = $options["method"];
    $_SESSION["service"]= $argumentsArray["service"];
    $_SESSION["method"] = $argumentsArray["method"];

} else {
    //see if I can handle this in the shutdown
    throw new \Exception('Missing service or method');
}

$serviceHandlerClass->makeServiceCall($argumentsArray);
?>
