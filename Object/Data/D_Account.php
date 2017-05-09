<?php

/**
 *
 *  账号对象映射
 * @author      Chang_back
 * @time        2016年10月8日
 * @descrbie    账号对象映射
 *
 */

namespace Object\Data;

set_include_path($_SERVER['DOCUMENT_ROOT'] . "/" . P01_ROOT_NAME);
require_once ('DB/db.php');
require_once ('Glolbal_Function/global_function.php');
require_once ('Object/Data/DataObject.php');

use DB;
use Object\DataObject;


class D_Account extends DataObject
{

    public static $table = "tbl_Account";

    protected function init()
    {

    }

    public static function create_object_by_id($id)
    {
        $str_sql = sprintf("SELECT *FROM %s WHERE id = %s", self::$table, $id);
        return self::create_object_by_sql($str_sql);
    }

    public static function create_obj_by_name($name)
    {
        $str_sql = sprintf("SELECT *FROM %s WHERE sm_accName = %s", self::$table, $name);
        return self::create_object_by_sql($str_sql);
    }

    public static function create_objs_by_name($name)
    {
        $str_sql = sprintf("SELECT *FROM %s WHERE sm_accName LIKE '%%%s%%' LIMIT 0, 10", self::$table, $name);
        return self::create_objects_by_sql($str_sql);
    }

}
?>