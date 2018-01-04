<?php
class File extends helper {

    public static $maxFileSize = 3000000;
    protected $name;
    protected $type;
    protected $size;
    protected $tmp_name;
    protected $error;

    public function __construct($file) {
        foreach($_FILES["$file"] as $attribute => $value) {
            $this->$attribute = $value;
        }
    }

    public static function deleteFile($destLoc) {
        return unlink($destLoc);
    }

    public function getImageSize() {
        return $this->size;
    }

    public function moveUploadedFile($destLoc) {
        $result = move_uploaded_file($this->tmp_name, $destLoc . $this->name);
        return $result;
    }

    public function getMemberVar($var) {
        if(property_exists(get_called_class(), $var)) {
            return $this->$var;
        } else {
            return false;
        }
    }
}
?>
