<?php
//Initialize all classes & configuration
require_once("includes/intialize.php");

if (isset($_GET['id'])) {
    $validid = pf_validate_number($_GET['id'], "value", $config_basedir);

    //Find the first item by id; return the object
    $itemObj = item::findFirstCond( array ( "id" => $validid));

    //Check if the object property 'user_id' matches the currently logged in user's id
    if($itemObj->getMemberVar('user_id') != $session->getUserObj()->getMemberVar('id')) {
        header("Location: index.php");
    }

}

// *********************** (A) code goes here! ***************************************
// ******* If user is not logged in, redirect to the login page **********************

if($session->getLoggedInStatus() == FALSE)
{

    header("Location: login.php?ref=images&id=$validid");
}


if(isset($_POST['submit'])) {

    //Create a file object containing the information about the uploaded file
    $file = new file('userfile');

    //If the file doesn't have a name, redirect to an error page
    if(!$file->getMemberVar("name")) {
        header("Location: addimages.php?error=nophoto");

        //If the file was empty, redirect to an error page
    } elseif(!$file->getMemberVar("size")) {
        header("Location: addimages.php?error=photoprob");

        //If the file exceeded the maximum file size (defined in the file class), redirect to an error page
    } elseif($file->getMemberVar("size") > file::$maxFileSize) {
        header("Location: addimages.php?error=large");

        //If the image file does not meet the sufficient size requirements, redirect to an error page
    } elseif(!$file->getImageSize()) {
        header("Location: addimages.php?error=invalid");

        //else; valid file
    } else {
        //Move the file to a new directory (specified in the file class) and rename the file.
        if($file->moveUploadedFile(image::$uploadDir)) {

            // ********************** (B) code goes here! ***************************************
            // ******  Write the method call to add a new image record into the image Table *****

            Image::createDB([
                "id" => 0,
                "item_id"=> $itemObj->getMemberVar("id"),
                "name"=> $file->getMemberVar('name')
            ]);

            //Redirect the user
            header("Location: addimages.php?id=" . $itemObj->getMemberVar("id"));
        }
        else {
            echo 'There was a problem uploading your file.<br />';
        }
    }
}
else {
    require("header.php");

    echo "<h1>Current images</h1>";

    if(isset($_GET['error'])){
        $errorMsg = image::displayError($_GET['error']);
        if ($errorMsg != false) {
            echo $errorMsg;
        }
    } else {
        //Load image objects into itemObj
        $itemObj->getImages();

        //Check if there are image objects attached to the given item
        if(empty($itemObj->getMemberVar('imageObj'))) {
            echo "No images.";
        }
        else {
            echo "<table>";
            //Iterate over each image object and display it to the user
            foreach($itemObj->getMemberVar('imageObj') as $img) {
                echo "<tr>";
                echo "<td><img src='images/" . $img->getMemberVar('name') . "' width='100'></td>";
                echo "<td>[<a href='deleteimage.php?image_id=" . $img->getMemberVar('id') . "&item_id=" . $itemObj->getMemberVar("id") . "'>delete</a>]</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>

        <form enctype="multipart/form-data" action="<?php echo pf_script_with_get($_SERVER['SCRIPT_NAME']); ?>" method="POST">
            <!-- ********************** (C) code goes here! *************************************** -->
            <!-- ***** Write the code to create a new form with 3 input fields ******* -->

            <input type="hidden" name="MAX_FILE_SIZE" value="3000000"/>
            Select file:<input type="file" name="userfile" />
            <input type="submit" value="Upload File" name="submit" />

        </form>

        When you have finished adding photos, go and <a href="<?php echo "itemdetails.php?id=" . $itemObj->getMemberVar('id'); ?>">see your item</a>!
        <?php
    }
}

require("footer.php");
?>
