<?php
// Start session
session_start();

// Loading (all) dependencies
require_once(__DIR__ . "/input.inc.php");
require_once(__DIR__ . "/storage.inc.php");
require_once(__DIR__ . "/navigation.inc.php");
require_once(__DIR__ . "/auth.inc.php");

// Load (all) data sources
$userStorage = new Storage(new JsonIO(__DIR__ . "/../data/users.json"));
$postStorage = new Storage(new JsonIO(__DIR__ . "/../data/posts.json"));

// Initialize Auth class
$auth = new Auth($userStorage);

// Create (all) global variables
$errors = [];