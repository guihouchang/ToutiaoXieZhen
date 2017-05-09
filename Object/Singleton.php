<?php
/**
 *
 *  单例模板
 * @author      Chang_back
 * @time        2016年10月8日
 * @descrbie    单例模板
 *
 */

class Singleton
{
    protected static $instances;

    protected function __construct() { }

    final private function __clone() { }


    public static function get_instance() {
        $class = get_called_class();

        if (!isset(self::$instances[$class])) 
        {
            self::$instances[$class] = new $class;
        }
        return self::$instances[$class];
    }


    public static function unsetInstance()
    {
        $class = get_called_class();
        unset(self::$instances[$class]);
    }


    public static function set_singleton_instance($instance)
    {
        $class = get_called_class();
        self::$instances[$class] = $instance;
    }
    }
?>