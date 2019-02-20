<?php
define("DB_HOST","DATABASE HOST");
define("DB_USER","DATABASE USER");
define("DB_PASSWORD","DATABASE PASSWORD");
define("DB_BASE","DATABASE NAME");

function loadClass($name) {
    require "model/$name.php";
}
spl_autoload_register("loadClass");