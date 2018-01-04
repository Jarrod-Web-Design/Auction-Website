<?php
require_once("includes/intialize.php");

// *********************** (A) code goes here! ***************************************
// ******* If user is not logged in, redirect to the home page ***********************
			
			$session->getLoggedInStatus();

            if ($session == false) {
            	header("Location: index.php");
            }



if(isset($_POST['submit'])) {
	$validdate = checkdate($_POST['month'], $_POST['day'], $_POST['year']);
    
	if($validdate == TRUE) {
		$concatdate = $_POST['year']
			. "-" . sprintf("%02d", $_POST['month'])
			. "-" . sprintf("%02d", $_POST['day'])
			. " " . $_POST['hour']
			. ":" . $_POST['minute']
			. ":00";
		
		// ********************** (B) code goes here! ***************************************
		// *******  Write the method call to record the new item into the items Table *******

        $itemObj = Item::createDB([
            "id" => 0,
            "user_id" => $session->getUserObj()->getMemberVar('id'),
            "cat_id" => $_POST['cat'],
            "name" => $_POST['name'],
            "startingprice" => $_POST['price'],
            "description" => $_POST['description'],
            "dateends" => $concatdate,
            "endnotified" => 0
        ]);

		header("Location: addimages.php?id=" . $itemObj->getMemberVar('id'));
	}
	else {
		header("Location: newitem.php?error=date");		
	}
}
else {
	require("header.php");
?>
	<h1>Add a new item</h1>
	<strong>Step 1</strong> - Add your item details.
	<p>
	<?php
	
		if (isset($_GET['error'])){
            $errorMsg = item::displayError($_GET['error']);
            if ($errorMsg != false) {
                echo $errorMsg;
            }
		}
	?>
	</p>	
	<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" >
	<table>
		<tr>
			<td>Category</td>
			<td>
			<select name="cat">
			<?php
			// ******************* (C) code goes here! ***************************************
			// ***  Write the method call to get all catagory records for display in FORM (below) *********

            $catObjs = Category::findByCond();

            //Display each categories properties
			foreach ($catObjs as $catObj) {
				echo "<option value='" . $catObj->getMemberVar('id') . "'>" . $catObj->getMemberVar('cat') . "</option>";
			}
			?>
			</select>
			</td>
		</tr>
	<tr>
		<td>Item name</td>
		<td><input type="text" name="name"></td>
	</tr>
	<tr>
		<td>Item description</td>
		<td><textarea name="description" rows="10" cols="50"></textarea></td>
	</tr>
	<tr>
		<td>Ending date</td>
		<td>
		<table>
			<tr>
				<td>Day</td>
				<td>Month</td>
				<td>Year</td>
				<td>Hour</td>
				<td>Minute</td>
			</tr>
			<tr>
				<td>
				<select name="day">
				<?php
					for($i=1;$i<=31;$i++) {
						echo "<option>" . $i . "</option>";
					}
				?>
				</select>
				</td>
				<td>
				<select name="month">
				<?php
					for($i=1;$i<=12;$i++) {
						echo "<option>" . $i . "</option>";
					}
				?>
				</select>
				</td>
				<td>
				<select name="year">
				<?php
					for($i=2016;$i<=2022;$i++) {
						echo "<option>" . $i . "</option>";
					}
				?>
				</select>
				</td>
				<td>
				<select name="hour">
				<?php
					for($i=0;$i<=23;$i++) {
						echo "<option>" . sprintf("%02d",$i) . "</option>";
					}
				?>
				</select>
				</td>
				<td>
				<select name="minute">
				<?php
					for($i=0;$i<=60;$i++) {
						echo "<option>" . sprintf("%02d",$i)  . "</option>";
					}
				?>
				</select>
				</td>
			</tr>
		</table>		
		</td>
	</tr>
	<tr>
		<td>Price</td>
		<td><?php echo $config_currency; ?><input type="text" name="price"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="Post!"></td>
	</tr>
	</table>
	</form>

<?php
}

require("footer.php");

?>