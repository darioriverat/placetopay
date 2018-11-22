<?php

// Set localtime zone
date_default_timezone_set("America/Bogota");

// Memory limit
ini_set("memory_limit","256M");

// Run application
require_once("vendor/autoload.php");

$mvc = new Drone\Mvc\Application(include "config/application.config.php");