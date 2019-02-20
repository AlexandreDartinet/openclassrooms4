<?php
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
