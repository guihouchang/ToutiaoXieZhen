<?php
/**
 *
 *  全局常量信息
 * @author       Chang_back
 * @file         global_const.php
 * @time         2016年02月17日
 *
 */

// 定义根目录路径
define("P01_ROOT_NAME", "P01");

class GLOBAL_CONST
{
    // 账号最大字符
    public static $MAX_ACCOUNT_LIMIT = 12;

    // 账号最小字符
    public static $MIN_ACCOUNT_LIMIT = 6;

    // 密码MD5秘钥
    public static $PSW_MD5_KEY = "71E46CDB7DDF99DE";

    // 是否开启密码验证
    public static $IS_NEED_PSW = true;

    public static $DEBUG_MODE = true;

    public static $UPDATE_MODE = false;

    // 获得道具富文本常量
    public static $AWARD_ITEM_STRING_TEXT = "<color=#ffffff>恭喜你，获得了<color=#ffda00>%s</color>数量<color=#0bfee1>%d</color>个。</color>";

    // 普通提示文本常量
    public static $NORMAL_STRING_TEXT = "<color=#ffffff>%s</color>";

    // 获得金币或者钻石文本常量
    public static $AWARD_CURRENCY_STRING = "<color=#ffffff>恭喜你，获得了<color=#ffda00>%s</color>数量<color=#0bfee1>%d</color>。</color>";

    // 升级提示信息
    public static $LEVELUP_STRING_TEXT = "<color=#ffffff>恭喜你，升到了<color=#ffda00>%s</color>级。</color>";

}

class GLOBAL_ACCOUNT_KEY {
    public static $RESTORE_POINTS_KEY = "restore_points";
}

class CONST_PROPERTY_VALUE_TYPE
{
    const VALUE = 1;        # 按数值加成
    const PERCENT = 2;      # 按万分比加成
}

class JSON_CONST_MSG
{
    // 失败
    public static $JSON_RETURN_FALID = '{"type":"error"}';
}