<?php
/**
 *
 * 数据对象基类
 * @author  Chang_back
 * @time    2016年10月10日
 * @describe 数据对象基类,所有数据对象都要继承该类
 *
 */
namespace Object;
set_include_path($_SERVER['DOCUMENT_ROOT'] . "/" . P01_ROOT_NAME);
require_once ('DB/db.php');
require_once ('Glolbal_Function/global_function.php');
use DB;


abstract class CONST_MYSQL_TYPE
{
    const _INT = "integer";
    const _STRING = "string";
    const _FLOAT = "float";
    static $MYSQL_TYPE = [
        self::_INT => "INT(11)",
        self::_STRING => "VARCHAR(255)",
        self::_FLOAT => "FLOAT",
    ];
}


class DataObject
{

    // TODO - Insert your code here
    protected $property_array = [];
    public static $FILTER_FILED_ARRAY = array(self::PRIMARY_KEY);
	const PRIMARY_KEY = "_id";
    public static $table;

    /**
     */
    function __construct($data)
    {
        $this->property_array = $data;
        $this->init();
    }


    protected function check_table()
    {
        if (self::$table == null || self::$table == "")
        {
            return false;
        }

        $str_sql = sprintf("SELECT table_name FROM information_schema.TABLES WHERE table_name ='%s'", self::$table);

        # 检查表是否存在
        $result_value = DB::get_instance()->query($str_sql);
        if ($result_value == null)
        {
            # todo
        }
    }

    protected function get_mysql_type($value)
    {
        $var_type = gettype($value);
        return CONST_MYSQL_TYPE::$MYSQL_TYPE[$var_type];
    }

    protected function create_table()
    {
        $str_sql  = "CREATE TABLE `%s` (
	                   `id` INT(11) NOT NULL AUTO_INCREMENT, %s
	                   PRIMARY KEY (`id`))";

        $content_sql = "";
        foreach($this->property_array as $k => $v)
        {
            $mysql_type = $this->get_mysql_type($v);
            if ($mysql_type != null)
            {
                $content_sql .= "`$k` $mysql_type NOT NULL,";
            }
        }

        if ($content_sql != "")
        {
            $content_sql = substr($content_sql, 0, strlen($content_sql) - 1);
            $str_sql = sprintf($str_sql, $content_sql);

            $ret_value = DB::get_instance()->query($str_sql);
            return $ret_value;
        }

        return false;
    }

    protected function init()
    {

    }

    /**
     */
    function __destruct()
    {

    }

    function __set($name, $value)
    {
        if (array_key_exists($name, $this->property_array))
        {
            if ($this->property_array[$name] != $value)
            {
                $this->property_array[$name] = $value;
                $this->update_field_data($name, $value);
            }
        }
    }

    function __get($name)
    {
        if (array_key_exists($name, $this->property_array))
        {
            return $this->property_array[$name];
        }

        return null;
    }

    public function delete_data()
    {
        $str_sql = sprintf("DELETE FROM %s WHERE id = %s", $this::$table, $this->id);
        return DB::get_instance()->query($str_sql);
    }

    protected function update_field_data($name, $value)
    {
        // 主键不允许更新
        if ($name == self::PRIMARY_KEY)
        {
            return;
        }

        $class = get_called_class();

        $str_sql = "";

        $value = is_string($value) ? "'" . $value . "'" : $value;

        if(in_array($name, $class::$FILTER_FILED_ARRAY) || strpos($class::$table, "usr_") !== false) # usr_关键字是用户手动设计的表
        {
            $str_sql = sprintf("UPDATE %s SET `%s` = %s WHERE id = %s", $this::$table, $name, $value, $this->id);
        }
        else
        {
            $str_sql = sprintf("UPDATE %s SET `sm_%s` = %s WHERE id = %s", $this::$table, $name, $value, $this->id);
        }


        return DB::get_instance()->query($str_sql);
    }

    public function fresh_data()
    {

    }

    protected function insert_one_data()
    {
        $dataArray = [];

        foreach($this->property_array as $k => $v)
        {
            $class = get_called_class();
            if(in_array($k, $class::$FILTER_FILED_ARRAY) || strpos($class::$table, "usr_") !== false) # usr_关键字是用户手动设计的表
            {
                $dataArray[$k] = $v;
            }
            else
            {
                $dataArray['sm_' . $k] = $v;
            }
        }

        if (DB::get_instance()->insert($this::$table, $dataArray))
        {
            $insert_id = DB::get_instance()->insert_id();
            $this->property_array['id'] = $insert_id;
            return $this;
        }
    }

    public static function destroy($obj, $is_delete_from_db = false)
    {
        if ($is_delete_from_db)
        {
            $obj->delete_data();
        }

        unset($obj);
    }

    public function get_property_array()
    {
        return $this->property_array;
    }

    public static function create_object_by_data($data, $is_insert_db = true)
    {
        $class = get_called_class();
        $obj = null;

        $obj = new $class($data);

        if ($is_insert_db)
        {
            return $obj->insert_one_data();
        }
        else
        {
            return $obj;
        }
    }

    public static function create_objects_by_parent_id($id)
    {
        $class = get_called_class();
        $str_sql  = sprintf("SELECT *FROM %s WHERE parentID = %d", $class::$table, $id);

        return  $class::create_objects_by_sql($str_sql);
    }

    public static function create_object_by_parent_id($id)
    {
        $class = get_called_class();
        $str_sql  = sprintf("SELECT *FROM %s WHERE parentID = %d", $class::$table, $id);

        return  $class::create_object_by_sql($str_sql);
    }

    public static function create_object_by_id($id)
    {
        $class = get_called_class();
        $str_sql = sprintf("SELECT *FROM %s WHERE parentID = %d", self::$table, $id);
        return $class::create_object_by_sql($str_sql);
    }

    public static function create_object_by_id_table($id, $table)
    {
        //self::create_object_by_id($id);
    }

    protected static function create_object_by_sql($str_sql)
    {
        $query_data = DB::get_instance()->get_one($str_sql);

        if ($query_data == null)
        {
            return null;
        }

        $query_data = replace_str_from_array_key('sm_', $query_data);
        $obj = null;
        $class = get_called_class();
        $obj = new $class($query_data);

        return $obj;
    }

    protected static  function create_objects_by_sql($str_sql)
    {
        $objs = [];

        $quest_data_array = DB::get_instance()->get_all($str_sql);

        if ($quest_data_array != null)
        {
            foreach($quest_data_array as $query_data)
            {
                $query_data = replace_str_from_array_key('sm_', $query_data);
                $class = get_called_class();
                $obj = new $class($query_data);
                $objs[] = $obj;
            }
        }

        return $objs;
    }


}
?>