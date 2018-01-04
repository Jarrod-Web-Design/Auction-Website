<?php require_once("includes/intialize.php"); ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php $config_auctionname; ?></title>
		<link rel="stylesheet" href="stylesheet.css" type="text/css" />
	</head>
	<body>
		<div id="header">
			<h1>Jarrod's Online Auction</h1>
		</div>

		<div id="menu">
			<a href="index.php">Home</a> &bull;
			
			<?php

			if($session->getLoggedInStatus()) {
				echo "<a href='logout.php'>Logout</a> &bull;";
			}
			else {
				echo "<a href='login.php'>Login</a> &bull;";
			}

			?>

			<a href="newitem.php">New Item</a> &bull;
			<a href="processauctions.php">Process Auction</a>
		</div>

		<div id="container">
			<div id="bar">
				<?php require_once("bar.php"); ?>
			</div>
			<div id="main">