<?php

	require_once('includes/intialize.php');

	if (isset($_GET['id'])) {
		$validid = pf_validate_number($_GET['id'], "value", $config_basedir);
	} else {
		$validid = 0;
	}

	require("header.php");

	if($validid == 0) {
		$itemObjs = item::findByCond("dateends > NOW()");
	}
	else {
		$itemObjs = item::findByCond("dateends > NOW() AND cat_id = " . $validid);
	}

	echo "<h1>Items Available</h1>";
	echo "<table cellpadding='5'>";
	echo "<tr>";
		echo "<th>Image</th>";
		echo "<th>Item</th>";
		echo "<th>Bids</th>";
		echo "<th>Price</th>";
		echo "<th>End Date for this Item</th>";
	echo "</tr>";

	if(!$itemObjs) {
		echo "<tr><td colspan=4>No items!</td></tr>";
	} else {
		foreach($itemObjs as $itemObj) {
			echo "<tr>";

			$itemObj->getImages();

			if(!$itemObj->getMemberVar('imageObj')) {
				echo "<td>No image</td>";
			} else {
				$imgObjs = $itemObj->getMemberVar('imageObj');
				$firstImg = array_shift($imgObjs);

				echo "<td><img src='./images/" . $firstImg->getMemberVar('name') . "' width='100'></td>";
			}

	echo "<td>";
	echo "<a href='itemdetails.php?id=" . $itemObj->getMemberVar('id') . "'>" . $itemObj->getMemberVar('name') . "</a>";

	if ($session->getLoggedInStatus()) {
		if($session->getUserObj()->getMemberVar('id') == $itemObj->getMemberVar('user_id')) {
			echo " - [<a href='edititem.php?id=" . $itemObj->getMemberVar('id') . "'>edit</a>]";
		}
	}

	echo "</td>";
	echo "<td>";

	$itemObj->getBids();

	if(!$itemObj->getMemberVar('bidObjs')) {
		echo "0";
	} else {
		echo count($itemObj->getMemberVar('bidObjs')) . "</td>";
	}

	echo "<td>" . $config_currency;

	if(!count($itemObj->getMemberVar('bidObjs'))) {
		echo sprintf('%.2f', $itemObj->getMemberVar('startingprice'));
	} else {
		$itembids = $itemObj->getMemberVar('bidObjs');
		$highestBidObj = array_shift($itembids);
		echo sprintf('%.2f', $highestBidObj->getMemberVar('amount'));
	}

	echo "</td>";

	echo "<td>" . date("D js F Y g.iA", strtotime($itemObj->getMemberVar('dateends'))) . "</td>";
	echo "</tr>";

}
}

echo "</table>";
require("footer.php");

?>