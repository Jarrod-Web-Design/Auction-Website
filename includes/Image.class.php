<?php

class Image extends helper {

	protected static $table_name = "images";

	protected $id;
	protected $item_id;
	protected $name;

    public static $uploadDir = "images/";
	public static $errorArray = array (
        "empty"=>"You did not select anything.","nophoto"=>"You did not select a photo to upload.","photoprob"=>"There appears to be a problem with the photo you're uploading.","large"=>"The photo you selected is too large.","invalid"=>"The photo you selected is not a valid image file."
    );

}

?>