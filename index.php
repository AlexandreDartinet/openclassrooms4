<?php
require('config.php');
require('init.php');
require('controller/frontend.php');

try {
    if (isset($_GET['action'])) {
        
    }
    else {
        listPosts();
    }
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
