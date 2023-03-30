<?php

if (!file_exists('../config.ini')){
    echo 'ERROR: NO CONFIG FILE - Add a "config.ini" file to the root of this project.';
    exit();
}

$config_ini = parse_ini_file('../config.ini');

$python_path = $config_ini['python_path'];
// This null check is fuzzy, and will say invalid if empty or doesn't exist:
if ($python_path == NULL) {
    echo 'ERROR: CONFIG IS INVALID - Fix your "config.ini" file.';
    exit();
}

?>