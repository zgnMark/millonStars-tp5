<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\vendor\log;

use app\vendor\log\PDOLogHandler;
use Monolog\Logger;
use PDO;
use PDOException;
use think\Config;
use think\log\driver\File;

/**
 * 日志类，优先写入数据库，没日志表时按TP框架日志方式
 */
class Systemlog extends File
{
    public static $logger;
    public static $db;
    //连接方式
    public static $connDbFlag;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->init();
    }

    public function init()
    {
        if (!empty(static::$logger)) {
            return;
        }

/*

//monolog的日志级别
self::DEBUG     => 'DEBUG', //详细的debug信息
self::INFO      => 'INFO',  //感兴趣的事件。像用户登录，SQL日志
self::NOTICE    => 'NOTICE', //正常但有重大意义的事件。
self::WARNING   => 'WARNING', //发生异常，使用了已经过时的API。
self::ERROR     => 'ERROR',  //运行时发生了错误，错误需要记录下来并监视，但错误不需要立即处理。
self::CRITICAL  => 'CRITICAL', //关键错误，像应用中的组件不可用。
self::ALERT     => 'ALERT', //需要立即采取措施的错误，像整个网站挂掉了，数据库不可用。这个时候触发器会通过SMS通知你，
self::EMERGENCY => 'EMERGENCY', //紧急提醒

//对应使用方法
Log::emergency($error);
Log::alert($error);
Log::critical($error);
Log::error($error);
Log::warning($error);
Log::notice($error);
Log::info($error);
Log::debug($error);

//下面是TP里的日志级别
const DEBUG  = 'debug';
const INFO   = 'info';
const NOTICE = 'notice';
const LOG    = 'log';
const SQL    = 'sql';
const ERROR  = 'error';
const ALERT  = 'alert';

 */
        try {
            $config   = Config::get();
            $dbConfig = $config['database'];
            //连接数据库
            if (empty(static::$db)) {

                $dbOptions = [
                    PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_AUTOCOMMIT => 1,
                ];
                $dsn = "mysql:host={$dbConfig['hostname']};dbname={$dbConfig['database']};port={$dbConfig['hostport']};charset={$dbConfig['charset']}";
                $db  = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbOptions);
                if ($db == null) {
                    throw new \Exception("连接数据库失败", 1);
                }
                static::$db = $db;
            }
            $channel = (isset($this->config['channel']) && !empty($this->config['channel'])) ? $this->config['channel'] : 'TP';
            $log     = new Logger($channel);
            //触发此处理程序的最小日志记录级别为DEBUG
            $level = $config['app_debug'] ? Logger::DEBUG : Logger::INFO;
            $log->pushHandler(new PDOLogHandler(static::$db, $level));
            static::$logger     = $log;
            static::$connDbFlag = true;
        } catch (\PDOException $e) {
            //连接不上写文件
            static::$connDbFlag = false;
        }

    }

    /**
     * 日志写入接口
     * @access public
     * @param array $log 日志信息
     * @return bool
     */
    public function save(array $log = [])
    {
        //原方式写入,或自定义写入
        if (!static::$connDbFlag) {
            parent::save($log);
            return true;
        }

        //写入数据库
        foreach ($log as $type => $val) {
            //这里做一个日志级别关联
            // Log::emergency($error);
            // Log::critical($error);
            switch ($type) {
                case 'debug':
                    static::$logger->debug('', $val);
                    break;
                case 'info':
                    static::$logger->info('', $val);
                    break;
                case 'notice':
                    static::$logger->notice('', $val);
                    break;
                case 'log':
                    static::$logger->warning('', $val);
                    break;
                case 'sql':
                    static::$logger->debug('', $val);
                    break;
                case 'alert':
                    static::$logger->alert('', $val);
                    break;
                case 'error':
                    static::$logger->error('', $val);
                    break;
                default:
                    static::$logger->info('', $val);
                    break;
            }
        }

        return true;
    }

}
