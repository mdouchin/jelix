<?php
/**
* @package  Jelix\Legacy
* @author   Laurent Jouanneau
* @contributor
* @copyright 2014 Laurent Jouanneau
* @link     http://www.jelix.org
* @licence  MIT
*/

$GLOBALS['JELIX_LEGACY_CLASSES'] = array(
    
);


function jelix_legacy_autoload($class) {
    if (isset($GLOBALS['JELIX_LEGACY_CLASSES'][$class])) {
        $f = $GLOBALS['JELIX_LEGACY_CLASSES'][$class];
        if (file_exists($f)) {
            require($f);
        }
    }
}

spl_autoload_register("jelix_legacy_autoload");
