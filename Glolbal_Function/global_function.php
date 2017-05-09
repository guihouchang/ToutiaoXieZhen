<?php
/**
 *
 * 全局函数
 *
 * @author Chang_back
 * @copyright global_function.php 2015年12月14日
 *
 */

require_once ("Config/Config.php");
require_once ('Object/Component/Inventory.php');
require_once ('Log/Log.php');

use Config\Config;
use Object\Component\CONST_ITEM_TYPE;
use Log\Log;


// 数字字母验证函数
function check_account($str, $num1='', $num2='') //数字字母正则
{
    if($num1 != '' && $num2 != '')
    {
        return (preg_match("/^[a-zA-Z][a-zA-Z0-9_]{".$num1.",".$num2."}$/",$str))? true : false;
    }
    else
    {
        return (preg_match("/^[a-zA-Z][a-zA-Z0-9_]/",$str)) ? true : false;
    }
}

// 获取更新函数
function get_update_sql($source_array, $target_array, $table, $condition)
{
    if (empty($source_array) || empty($target_array) || empty($table) || empty($condition))
    {
        return false;
    }

    $need_update_array = "";

    foreach ($target_array as $tk=>$tv)
    {
        foreach ($source_array as $sk=>$sv)
        {
            if ($tk == $sk)
            {
                $need_update_array[$tk] = $tv;
            }
        }
    }

    if (! empty($need_update_array))
    {
        $str_sql = "UPDATE " . $table . " SET ";
        foreach ($need_update_array as $k => $v)
        {
            $str_sql .= $k . " = " . $v . ",";
        }

        $str_sql = substr($str_sql, 0, -1);

        return $str_sql. " WHERE " . $condition;
    }
}

// 生成唯一gid
function create_one_gid()
{
    return md5(uniqid());
}

function get_ip(){
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif(!empty($_SERVER["REMOTE_ADDR"])){
        $cip = $_SERVER["REMOTE_ADDR"];
    }
    else{
        $cip = "无法获取！";
    }
    return $cip;
}

function replace_str_from_array_key($str_key_word, $source_arry)
{
    $retArray = [];
    foreach ($source_arry as $key => $value)
    {
        $newKey = str_replace($str_key_word, "", $key);
        if (is_array($value))
        {
            $retArray[$newKey] = replace_str_from_array_key($str_key_word, "", $value);
        }
        else
        {
            $retArray[$newKey] = $value;
        }
    }
    return $retArray;
}

// 获取$_REQUEST的para参数
function get_param_from_request($request)
{
    $ret_array = [];

    foreach ($request as $k => $v) {
        if (strpos($k, "arg") !== false)
        {
            $ret_array[] = urldecode($v);
        }
    }

    return $ret_array;
}

// 解析类似2001:5,0:1000字段,返回key = 2001 => 5这样的数组
function parse_string_to_array($str)
{
    $ret_value = [];
    $exp_array = explode(",", $str);
    foreach($exp_array as $v)
    {
        $tmp_array = explode(":", $v);
        $ret_value[$tmp_array[0]] = $tmp_array[1];
    }

    return $ret_value;
}

// 解析类似2001:5,0:1000字段，返回二维数组
function parse_string_to_array_ex($str)
{
    $ret_value = [];
    $exp_array = explode(",", $str);
    foreach($exp_array as $v)
    {
        $tmp_array = explode(":", $v);
        $ret_value[] = [$tmp_array[0] => $tmp_array[1]];
    }

    return $ret_value;
}

/**
 *  将字符串转换为带权重的随机数组
 * @param string $str：101001:300,101002:300,101003:300
 */
function convert_to_random_list($str, $first = ",", $second = ":")
{
    if ($str == null)
        return;

    $ret_value = [];
    $explode_first_list = explode($first, $str);
    foreach($explode_first_list as $explode_str)
    {
        $data_list = explode($second, $explode_str);
        $ret_value[] = ["value" => $data_list[0], "weight" => $data_list[1]];
    }

    return $ret_value;
}

// 按权重随机
function random_by_weight($list, $weight = null)
{
    if ($weight == null)
    {
        foreach($list as $v)
        {
            $weight += $v['weight'];
        }
    }

    $rand_num = mt_rand(1, $weight);
    $cur_weight = 0;
    foreach($list as $k => $v)
    {
        $cur_weight += $v["weight"];
        if ($cur_weight >= $rand_num)
        {
            $ret_value = $v;
            $ret_value["random_key"] = $k;
            return $ret_value;
        }
    }
}

function random_by_weight_count($list, $count = 1, $weight = null)
{
    $ret_value = [];
    for($i = 0; $i < $count; $i++)
    {
        $tmp_list = $list;
        $rand_item = random_by_weight($tmp_list);
        unset($list[$rand_item["random_key"]]);
        $ret_value[] = $rand_item;
    }

    return $ret_value;
}

function string2array($string, $split_flag = ',')
{
    $split_array = explode($split_flag, $string);
    return $split_array;
}

function array2string($array, $split_flag = ',')
{
    $string = "";
    foreach($array as $element)
    {
        $string .= trim($element) . $split_flag;
    }

   return substr($string, 0, -1);
}

function get_item_name($item_id)
{
    if ($item_id == CONST_ITEM_TYPE::GEM)
    {
        return "钻石";
    }
    else if ($item_id == CONST_ITEM_TYPE::GOLD)
    {
        return "金币";
    }
    else if($item_id == CONST_ITEM_TYPE::HONOR)
    {
        return "荣誉";
    }
    else if ($item_id == CONST_ITEM_TYPE::PHY_POWER)
    {
        return "体力";
    }
    else
    {
        $item_base = Config::get_instance()->get_data_by_id('aghoul_itembase_cfg', $item_id);
        if ($item_base != null)
        {
            return $item_base["name"];
        }
    }

}

function get_item_cfg_data($item_id)
{
    $item_base = Config::get_instance()->get_data_by_id('aghoul_itembase_cfg', $item_id);
    if ($item_base == null) {
        Log::Error(sprintf("Inventory::get_item_cfg_data, item_id:%d not found!", $item_id));
        return null;
    }

    if ($item_base['type'] == CONST_ITEM_TYPE::EQUIP) {
        $detal_data = Config::get_instance()->get_data_by_id('aghoul_equip_cfg', $item_base['item_id']);
        if ($detal_data != null) {
            unset($detal_data['id']);
            $item_base = array_merge($item_base, $detal_data);
        }
    }
    else if ($item_base['type'] == CONST_ITEM_TYPE::MATERIAL)
    {

    }
    else if ($item_base['type'] == CONST_ITEM_TYPE::GEM_STONE)
    {
        $detal_data = Config::get_instance()->get_data_by_id('aghoul_gem_cfg', $item_base['item_id']);
        if ($detal_data != null)
        {
            unset($detal_data['id']);
            $item_base = array_merge($item_base, $detal_data);
        }
     }
    else if ($item_base['type'] == CONST_ITEM_TYPE::TREASURE)
    {

    }
    else if ($item_base['type'] == CONST_ITEM_TYPE::INSCRIPTION)
    {
        $detal_data = Config::get_instance()->get_data_by_id('aghoul_genius_cfg', $item_base['item_id']);
        if ($detal_data != null)
        {
            unset($detal_data['id']);
            $item_base = array_merge($item_base, $detal_data);
        }

    }
    else if ($item_base['type'] == CONST_ITEM_TYPE::GIFT)
    {
        $detal_data = Config::get_instance()->get_data_by_id('aghoul_gift_cfg', $item_base['item_id']);
        if ($detal_data != null)
        {
            unset($detal_data['id']);
            $item_base = array_merge($item_base, $detal_data);
        }
    }
    else if ($item_base['type'] == CONST_ITEM_TYPE::ARTIFACT)
    {
        $detal_data = Config::get_instance()->get_data_by_id('aghoul_artifact_debris_cfg', $item_base["item_id"]);
        if($detal_data != null)
        {
            unset($detal_data['id']);
            $item_base = array_merge($item_base, $detal_data);
        }
    }
    else if($item_base["type"] == CONST_ITEM_TYPE::USE_ITEM)
    {
        $use_data = Config::get_instance()->get_data_by_id('aghoul_useitem_cfg', $item_base["item_id"]);
        if($use_data != null)
        {
            unset($detal_data['id']);
            $item_base = array_merge($item_base, $use_data);
        }
    }

    return $item_base;
}

function send_to_client($arg, $callback)
{
    $json_array = array();
    $json_array = $arg;
    $json_array['count'] = count($arg);
    $json_array['callback'] = $callback;

    if ($_SERVER['JSON_DATA'] != "")
    {
        $_SERVER['JSON_DATA'] .= "," . json_encode($json_array, JSON_NUMERIC_CHECK);
    }
    else
    {
        $_SERVER['JSON_DATA'] = json_encode($json_array, JSON_NUMERIC_CHECK);
    }
}

function reward_tips($reward_list, $title = "获得奖励")
{
    send_box_to_client($title, $reward_list);
}

// 远程调用提示消息
function send_tips_to_client($str_content)
{
    send_to_client([$str_content], "OnShowTips");
}

function send_box_to_client($title, $id_list)
{
    send_to_client([$title, $id_list], "OnShowBox");
}

function convert_msg_to_client($header, $content)
{
    echo "{\"header\":$header, \"content\":[$content]}";
}

// 远程调用弹出框消息
function send_messagebox_to_client($str_content)
{

}

?>