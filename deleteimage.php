<?php
require_once("includes/initialize.php");

if (isset($_GET['image_id']) || isset($_GET['item_id'])) {
    $validimageid = pf_validate_number($_GET['image_id'], "redirect", $config_basedir);
    $validitemid = pf_validate_number($_GET['item_id'], "redirect", $config_basedir);
} else {
    die("Invalid ID");
}

if(isset($_POST['submityes'])) {
   
		//Retrieve the item and image from the database and instaniate a new instance of the item and image class
		$itemObj = item::findFirstByCond( array("id" => "$validitemid"));
		$imageObj = image::findFirstByCond( array ("id" => "$validimageid"));

		// *********************** (A) code goes here! ***************************************
		// ** Invoke the method to delete the file from the server **********************
		// **** Use the method we created in the File class ******************

        $imageObj->deleteFile($uploadDir);

		
		image::deleteDB( array ( "name" => $imageObj->getMemberVar("name")));	
		header("Location: addimages.php?id=" . $itemObj->getMemberVar("id"));

}

if (isset($_POST['submitno'])) {
	header("Location: addimages.php?id=" . $validitemid);
}

require("header.php");
?>

	<h2>Delete image?</h2>
	<form action="<?php echo pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="post">
	Are you sure you want to delete this image?
	<p>
	<input type="submit" name="submityes" value="Yes"> <input type="submit" name="submitno" value="No">
	</p>
	</form>

<?php
require("footer.php");
?>


