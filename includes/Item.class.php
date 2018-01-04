<?php
class Item extends helper {
    protected static $table_name = "items";

    protected $id;
    protected $user_id;
    protected $cat_id;
    protected $name;
    protected $startingprice;
    protected $description;
    protected $dateends;
    protected $endnotified;
    protected $imageObj = [];
    protected $bidObjs = array();
    public static $errorArray = array("lowprice"=>"The bid entered is too low. Please enter another price.", "letter"=>"The value entered is not a number.", "date"=>"Invalid date - please choose another!");

    public function getImages() {
        $this->imageObj = image::findByCond(array("item_id" => $this->id));
    }

    public function getBids() {
        $this->bidObjs = bid::findByCond(array("item_id" => $this->id), null, "amount DESC");
    }

    public function setCatId($newCatid) {
        $this->cat_id = $newCatid;
    }

    public function setName($newName) {
        $this->name = $newName;
    }

    public function setDescription($newDescription) {
        $this->description = $newDescription;
    }

    public function setDateEnds($newEndDate) {
        $this->dateends = $newEndDate;
    }

    public function setStartingPrice($newPrice) {
        $this->startingprice = $newPrice;
    }
}
?>
