<?php 

require_once('../connexion.php');
session_start();
include("lang.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="css/catalogue.css" rel="stylesheet" />
<?php if($_SESSION['lang']=="ar") { ?>
<link href="css/catalogueAr.css" rel="stylesheet" />
<?php } else { ?>
<link href="css/catalogueEn.css" rel="stylesheet" />
<?php } ?>

<style media="screen" type="text/css">
#page,#piedPage{	border-left:1px solid #778;border-right:1px solid #778; -moz-box-shadow:0px 0px 20px #666;}
.style1 {font-size: 12pt}
</style>
</head>

<body>
  <input type="button" onclick=" fermer() " value="" class="Fermer" style="display:none;"/>
<div id="page"> 