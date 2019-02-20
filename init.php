<?php
session_start();

function loadClass($name) {
    require "model/$name.php";
}
spl_autoload_register("loadClass");

if(!isset($_SESSION["user"])) {
    $_SESSION["user"] = new User([
        "id" => 0,
        "name" => "Anonyme",
        "password" => "",
        "mail" => "nothing@anonymous.fr",
        "date_inscription" => date("Y-m-d H:i:s"),
        "last_seen" => date("Y-m-d H:i:s"),
        "level" => User::LEVEL_ANON,
        "ip" => $_SERVER["REMOTE_ADDR"],
        "name_display" => "Anonyme"
    ]);
}