<?php spl_autoload_register(function($className)
{


    $class = './' . str_replace("Linguistico\\", '', $className) . '.php';
	$class = str_replace('\\', '/', $class);
    
    # Check if Class Exists & Include
    if (file_exists($class))
        require($class);
});
