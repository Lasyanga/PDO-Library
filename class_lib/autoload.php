<?php
	/*
		Credit to riptutorial.com for this wonderful autoloader.

		This will automatically require the right php class.

		Usage:
			require_once('./class_lib/autoload.php');

		NOTE:
			The class name should the same with the filename of the .php file.

			Example:

				<?php
					class SampleClass{
						public function __construct(){
							echo "Sample Class";
						}
					}
				?>

				It should name: SampleClass.php


		For more information about this class autoloader visit:
			https://riptutorial.com/php/example/13197/autoloading
	*/

	spl_autoload_register(function ($className) {
    $path = sprintf('%1$s%2$s%3$s.php',
    // %1$s: get absolute path
    realpath(dirname(__FILE__)),
     // %2$s: / or \ (depending on OS)
     DIRECTORY_SEPARATOR,
     // %3$s: don't wory about caps or not when creating the files
     strtolower(
         // replace _ by / or \ (depending on OS)
         str_replace('_', DIRECTORY_SEPARATOR, $className))
        );
    if (file_exists($path)) {
        require_once $path;    
    } else  {
        throw new Exception(
            sprintf('Class with name %1$s not found. Looked in %2$s.', $className,$path ) 
        );
    } 
	});

	// $class = new SampleClass(); # sample how to create instance
?>