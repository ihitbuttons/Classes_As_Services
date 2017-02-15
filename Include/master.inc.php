<?php
/* Include Required Defines */
require_once(PATH_ROOT . 'Platform/Implementors/AutoLoader_Class.php');

/* Set default functions */
define('DEFAULT_SHUTDOWN_FUNCTION', '\Platform\Implementors\Shutdown_Class');
define('DEFAULT_ERROR_HANDLER', '\Platform\Implementors\ErrorHandler_Class');
define('DEFAULT_LOGGING_CLASS', '\Platform\Implementors\OutputLogging_Class');

/* Set the default version */
define('CURRENT_VERSION', '1');
define('CURRENT_LOGGING_LEVEL', 10);

/* Response Defines */
define('SUCCESS_FALSE', 'Failed');
define('SUCCESS_TRUE', 'Success');

/* Set default max lengths*/
define('SHORT_STRING_MAX_LENGTH', 8);
define('MAX_LENGTH_DEFINE', 140);

//build the modules array
$includedModulesArray = [];

//Platform
$includedModulesArray['Platform'] = ["base_dir" => PATH_ROOT . 'Platform', "prepend" => false];

//Services
$includedModulesArray['Services'] = ["base_dir" => PATH_ROOT . 'Services', "prepend" => false];

//Examples
$includedModulesArray['Examples'] = ["base_dir" => PATH_ROOT . 'Examples', "prepend" => false];

//instantiate the Auto Loader
$loader = new EBW\Framework\Implementors\AutoLoader_Class();

// register the base directories for the namespace prefix
$loader->addNamespaces($includedModulesArray);

//register the shutdown function
$shutdownFunction = constant('DEFAULT_SHUTDOWN_FUNCTION');
$shutdownFunctionInstance = $shutdownFunction::getInstance([]);
register_shutdown_function([&$shutdownFunctionInstance, 'shutdownFunction']);

$errorHandler = constant('DEFAULT_ERROR_HANDLER');
$errorHandlerInstance = $errorHandler::getInstance([]);
set_error_handler([&$errorHandlerInstance, 'customErrorHandler']);
