<?php
class helper
{
    public static function findByCond($cond = null, $groupBy = null, $orderBy = null, $limit = null)
    {
        $db = database::getConnection();
        $sql = "SELECT * FROM " . static::$table_name;

        if (is_array($cond)) {
            $sql .= " WHERE ";
            foreach ($cond as $attribute => $condition) {
                $sql .= " $attribute = :$attribute AND";
            }
            $sql = rtrim($sql, " AND");
        } else if ($cond != null) {
            $sql .= " WHERE $cond";
        }

        if (isset($groupBy)) {
            $sql .= " GROUP BY $groupBy";
        }

        if (isset($orderBy)) {
            $sql .= " ORDER BY $orderBy";
        }

        if (isset($limit)) {
            $sql .= " LIMIT $limit";
        }

        $results = $db->fetchArray($sql, $cond);
        return static::instantiateObjArr($results);
    }

    protected static function instantiateObjArr($items = null)
    {
        if (is_array($items)) {
            foreach ($items as $item) {
                if (!empty($item)) {
                    $obj = new static();

                    foreach ($item as $attribute => $value) {
                        $obj->setMemberVar("$attribute", $value);
                    }
                    $item_array[] = $obj;
                }
            }
            return $item_array;
        } else {
            return array();
        }
    }

    public static function findAll($groupBy = null, $orderBy = null)
    {
        return static::findByCond(null, $groupBy, $orderBy, null);
    }

    public static function findFirstCond($cond = null, $groupBy = null)
    {
        $objs = static::findByCond($cond, $groupBy, null, 1);
        return array_shift($objs);
    }

    public function getMemberVar($var)
    {
        if (property_exists(get_called_class(), $var)) {
            return $this->$var;
        } else {
            return false;
        }
    }

    public function setMemberVar($var, $value)
    {
        if (property_exists(get_called_class(), $var)) {
            $this->$var = $value;
            return true;
        } else {
            return false;
        }
    }

    public static function displayError($errorCode)
    {
        foreach (static::$errorArray as $error => $message) {
            if ($error == $errorCode) {
                return $message;
            }
        }
        return false;
    }

    private function prepareSQL($tablename)
    {
        $db = database::getConnection();
        $columnNames = array();
        $table_data = $db->fetchArray("DESCRIBE " . $tablename);

        if (is_array($table_data)) {
            foreach ($table_data as $data) {
                $row_data = $data;

                if (property_exists(get_called_class(), $row_data['Field'])) {
                    $columnNames[$row_data['Field']] = $this->$row_data['Field'];
                }
            }
        }
        return $columnNames;
    }

    private function insertDB() {
        $db = database::getConnection();

        $bindVals = static::prepareSQL(static::$table_name);

        $sql = "INSERT INTO " . static::$table_name;
        $sql .= " VALUES (";
        foreach ($bindVals as $attribute => $value) {
            $sql .= " :$attribute,";
        }
        $sql = rtrim($sql, ",");
        $sql .= ")";

        $db->sqlBindQuery($sql, $bindVals);

        return $db->lastInsertId();
    }

    public function updateDB() {
        $db = database::getConnection();

        $bindVals = static::prepareSQL(static::$table_name);
        if (!is_array($bindVals)) {
            return false;
        }
        $sql = "UPDATE ". static::$table_name;
        $sql .= " SET";
        foreach ($bindVals as $attribute => $value) {
            $sql .= " $attribute=:$attribute,";
        }
        $sql = rtrim($sql, ",");
        $sql .= " WHERE id=$this->id";

        $result = $db->sqlBindQuery($sql, $bindVals);

        return $result;
    }

    public static function createDB($attributes = array(), $condition = array()) {
        if(!empty($attributes)) {
            if(!empty($condition)) {
                $condObj = static::findFirstCond($condition);
                if($condObj != false) {
                    return false;
                }
            }

            $obj = new static;

            foreach ($attributes as $attribute => $value) {
                $obj->setMemberVar($attribute, $value);
            }

            $newId = $obj->insertDB();

            if($newId) {
                $obj->id = $newId;
                return $obj;
            } else {
                return false;
            }
        }
    }

    public static function deleteDB($cond) {
        $db = database::getConnection();

        $sql = "DELETE FROM " . static::$table_name;
        $sql .= " WHERE ";
        foreach($cond as $attribute => $value) {
            $sql .= " $attribute=:$attribute AND";
        }
        $sql = rtrim($sql, " AND");

        $result = Database::sqlBindQuery($sql, $cond);
        return $result;
    }

}
?>