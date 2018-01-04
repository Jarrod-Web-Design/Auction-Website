<html>
	<head>
		<title>Creating Tables</title>
	</head>
	<body>
		<?php
			require_once('includes/intialize.php');


			$dbc = Database::getConnection();
			$queryArray = array();

			$createBidsTable = "CREATE TABLE bids (id INT AUTO_INCREMENT PRIMARY KEY, item_id INT, amount FLOAT(7,2), user_id INT)";
			$createItemsTable = "CREATE TABLE items (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT, cat_id TINYINT, name VARCHAR(100), startingprice FLOAT(7,2), description TEXT, dateends DATETIME, endnotified TINYINT)";
			$createUsersTable = "CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(10), password VARCHAR(40), email VARCHAR(100), verifystring VARCHAR(20), active INT)";
			$createCategoriesTable = "CREATE TABLE categories (id INT AUTO_INCREMENT PRIMARY KEY, cat VARCHAR(20))";
			$createImagesTable = "CREATE TABLE images (id INT AUTO_INCREMENT PRIMARY KEY, item_id INT, name VARCHAR(100))";

			array_push($queryArray, $createBidsTable);
			array_push($queryArray, $createItemsTable);
			array_push($queryArray, $createUsersTable);
			array_push($queryArray, $createCategoriesTable);
			array_push($queryArray, $createImagesTable);

			$insertCategories1 = "INSERT INTO categories VALUES ('0', 'Scenery')";
			$insertCategories2 = "INSERT INTO categories VALUES ('0', 'Wildlife')";
			$insertUsers = "INSERT INTO users VALUES ('0', 'Jarrod', 'Foseball13', 'Jarrod.Maeckeler38@stclaircollege.ca', '', '1')";
			$insertItems1 = "INSERT INTO items VALUES ('0', '1', '1', 'Lake Huron Sunset', '50.00','Sunset on the east shore of Lake Huron', '2017-11-15 11:45:00', '0')";
			$insertItems2 = "INSERT INTO items VALUES ('0', '1', '1', 'October Reflection', '29.99','Fall relection at the Pinery Provincial Park', '2017-11-15 11:45:00', '0')";

			array_push($queryArray, $insertCategories1);
			array_push($queryArray, $insertCategories2);
			array_push($queryArray, $insertUsers);
			array_push($queryArray, $insertItems1);
			array_push($queryArray, $insertItems2);

			foreach($queryArray as $query) {
				$dbc->sqlBindQuery($query);
			}

		?>



	</body>


</html>