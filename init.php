<?php


function loadClass($name) {
    require "model/$name.php";
}
spl_autoload_register("loadClass");
session_start();
if(!isset($_SESSION["user"])) {
    $_SESSION["user"] = User::default();
}