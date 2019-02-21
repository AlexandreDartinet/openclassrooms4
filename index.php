<?php
require('config.php');
require('init.php');
require('controller/frontend.php');

try {
    if (preg_match('/^\/post\//', $_GET['path'])) {
        $id = (int) preg_replace('/^\/post\/(\d+)\//', '$1', $_GET['path']);
        viewPost($id);
    }
    else {
        listPosts();
    }
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
