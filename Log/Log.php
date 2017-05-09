<?php
/**
 *  Log类
 * @author      Chang_back
 * @time        2016年10月12日
 * @describe    提供Log的输出
 *
 */

namespace Log;
set_include_path($_SERVER['DOCUMENT_ROOT'] . "/" . P01_ROOT_NAME);

class ConfigData{
   public static function getConfig (){
      return array(
           'LOG_FILE'=> $_SERVER['DOCUMENT_ROOT'] . "/" . P01_ROOT_NAME . "/Log/Logs/" . date("Y-m-d") . '.log',
           'LOG_LEVEL'=>75  //INFO
      );
   }
}
class Log{

    private static $LogFile;
    private static $logLevel;

    const DEBUG  = 100;
    const INFO   = 75;
    const NOTICE = 50;
    const WARNING =25;
    const ERROR   = 10;
    const CRITICAL = 5;

    private function __construct(){

        $cfg = ConfigData::getConfig();
        $this->logLevel = isset($cfg['LOG_LEVEL']) ? $cfg['LOG_LEVEL']:LOG::INFO;
        if(!isset($cfg['LOG_FILE']) && strlen($cfg['LOG_FILE'])){
            //throw new Exception('can\'t set file to empty');
        }
        $this->LogFile = @fopen($cfg['LOG_FILE'],'a+');
        if(!is_resource($this->LogFile)){
            //throw new Exception('invalid file Stream');
        }

    }

    private static function LogMessage($msg, $logLevel = Log::INFO, $module = null){

        $cfg = ConfigData::getConfig();
        self::$logLevel = isset($cfg['LOG_LEVEL']) ? $cfg['LOG_LEVEL']:LOG::INFO;
        if(!isset($cfg['LOG_FILE']) && strlen($cfg['LOG_FILE'])){
            //throw new Exception('can\'t set file to empty');
        }

        self::$LogFile = @fopen($cfg['LOG_FILE'],'a+');
        if(!is_resource(self::$LogFile))
        {
            //throw new Exception('invalid file Stream');
        }

        if($logLevel > self::$logLevel){
            return ;
        }

        date_default_timezone_set('Asian/shanghai');

        $time = date('y-m-d h:i:s',time());
        $msg = str_replace("\t",'',$msg);
        $msg = str_replace("\n",'',$msg);

        $strLogLevel = self::levelToString($logLevel);

        if(isset($module)){
            $module = str_replace(array("\n","\t"),array("",""), $module);
        }

        //$logLine = "$time\t$msg\t$strLogLevel\t$module\r\n";
        $logLine = "[$strLogLevel] \t [$time] \t - $msg \r\n";
        fwrite(self::$LogFile, $logLine);
    }

    public static function Info($msg)
    {
        self::LogMessage($msg, Log::INFO);
    }

    public static function Notice($msg)
    {
        self::LogMessage($msg, Log::NOTICE);
    }

    public static function Error($msg)
    {
        self::LogMessage($msg, Log::ERROR);
    }

    public static function Warning($msg)
    {
        self::LogMessage($msg, LOG::WARNING);
    }

    private static function levelToString($logLevel){
         $ret = '[unknow]';
         switch ($logLevel){
                case LOG::DEBUG:
                     $ret = 'LOG::DEBUG';
                     break;
                case LOG::INFO:
                     $ret = 'LOG::INFO';
                     break;
                case LOG::NOTICE:
                     $ret = 'LOG::NOTICE';
                     break;
                case LOG::WARNING:
                     $ret = 'LOG::WARNING';
                     break;
                case LOG::ERROR:
                     $ret = 'LOG::ERROR';
                     break;
                case LOG::CRITICAL:
                     $ret = 'LOG::CRITICAL';
                     break;
         }
         return $ret;
    }
}

?>