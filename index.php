<?php
function loadClass($name) {
    require "model/$name.php";
}
spl_autoload_register("loadClass");
include('config.php');
require('controller/frontend.php');

try {
    if (isset($_GET['action'])) {
        
    }
    else {
        
    }
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
