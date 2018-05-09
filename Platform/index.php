<?php 
// Start Session
session_start();


// Include Config
require('config.php');

require('classes/bootstrap.php');
require('classes/Controllers.php');
require('classes/Model.php');
require('classes/messages.php');

require('controllers/home.php');
require('controllers/shares.php');
require('controllers/users.php');
require('controllers/dashboard.php');
require('controllers/student.php');

require('models/home.php');
require('models/share.php');
require('models/user.php');
require('models/dashboard.php');
require('models/student.php');

$bootstrap = new Bootstrap($_GET);

$controller = $bootstrap->createController();

if($controller){
	$controller->executeAction();
}

 ?>