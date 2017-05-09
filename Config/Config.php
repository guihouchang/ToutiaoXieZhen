<?php
/**
 *
 *  Config对象
 * @author      Chang_back
 * @time        2016年10月12日
 * @descrbie    管理配置信息
 *
 */

namespace Config;

set_include_path($_SERVER['DOCUMENT_ROOT'] . "/" . P01_ROOT_NAME);
require_once ('Object/Singleton.php');
require_once 'Log/Log.php';

use \Log\Log;

class Config extends \Singleton
{
    const ROOT_PATH = "../../";
    private $m_data_list = [];

    protected function __construct()
    {

    }

    function __set($name, $value)
    {

    }

    function __get($name)
    {
        if ($this->m_data_list[$name]  != null)
        {
            return $this->m_data_list[$name];
        }

        $str_json = file_get_contents(self::ROOT_PATH . "Config/Data/" . $name . ".json");

        if ($str_json == null)
        {
            return false;
        }

        $this->m_data_list[$name] = json_decode(trim($str_json, chr(239).chr(187).chr(191)));

        return $this->m_data_list[$name];
    }

    function get_data_by_id($json_name, $id)
    {
        $data = $this->$json_name;

        foreach ($data as $v)
        {
            if ($v->id == $id)
            {
                return (array)$v;
            }
        }
    }

    function get_data_by_array($json_name, $array_data)
    {
        $json_data = $this->$json_name;
        $find_data = null;

        if ($json_data != null)
        {
            foreach($json_data as $data)
            {
                $is_finded = true;
                if ($data != null && is_array($data))
                {
                    foreach($array_data as $key => $search_data)
                    {
                        if (!array_key_exists($key, $data))
                        {
                            $is_finded = false;
                        }
                    }

                    if ($is_finded)
                    {
                        return $data;
                    }
                }
            }
        }
    }

    function get_data_by_field($json_name, $field, $value)
    {
        $ret_value = [];
        $data = $this->$json_name;

        if($data != null)
        {
            foreach($data as $v)
            {
                if($v->$field == $value)
                {
                    $ret_value[] = (array)$v;
                }
            }
        }

        return $ret_value;
    }

}

?>