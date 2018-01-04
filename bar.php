<?php 

$catObjs = category::findAll('cat');

echo "<h1>Categories</h1>";
echo "<ul>";
	echo "<li><a href='index.php'>View All</a></li>";

foreach ($catObjs as $catObj) {
	echo "<li><a href='index.php?id=" . $catObj->getMemberVar('id') . "'>" . $catObj->getMemberVar('cat') . "</a></li>";
}

echo "</ul>";

?>