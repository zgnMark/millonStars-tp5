<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\vendor\log;

use app\vendor\log\PDOLogHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PDO;
use PDOException;
use think\Config;

/**
 * 日志类，优先写入数据库，没日志表时按TP框架日志方式写入文件
 * 使用方式:
 * 1、定义
 * use app\vendor\log\Monolog;
 * 2、静态或方法调用
 *      $obj = new Monolog;
 *      $obj::$logger->info('日志消息', ['上下文环境变量']);
 *      或
 *      $obj->info('日志消息', ['上下文环境变量']);
 *      或
 *      Monolog::info('日志消息', ['上下文环境变量']);
 *
 * 3、除了info还有以下方法
 *  Monolog::emergency($error,[]);
 *  Monolog::alert($error,[]);
 *  Monolog::critical($error,[]);
 *  Monolog::error($error,[]);
 *  Monolog::warning($error,[]);
 *  Monolog::notice($error,[]);
 *  Monolog::info($error,[]);
 *  Monolog::debug($error,[]);
 *
 */
class Monolog
{
    public static $logger;
    protected static $db;

    public function __construct()
    {
        static::init();
    }

    public static function init()
    {
        if (static::$logger) {
            return;
        }
        $config   = Config::get();
        $dbConfig = $config['database'];
        //判断是否写数据库
        if (!empty($config['log']['log_table'])) {

            try {
                //连接数据库
                if (empty(static::$db)) {
                    $dbOptions = [
                        PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_AUTOCOMMIT => 1,
                    ];

                    $dsn = "mysql:host={$dbConfig['hostname']};port={$dbConfig['hostport']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
                    $db  = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbOptions);
                    if ($db == null) {
                        throw new \Exception("连接数据库失败", 1);
                    }
                    static::$db = $db;
                }

                $log = new Logger('APP');
                //触发此处理程序的最小日志记录级别为
                $level = $config['app_debug'] ? Logger::DEBUG : Logger::INFO;
                $log->pushHandler(new PDOLogHandler(static::$db, $level));
                static::$logger = $log;
            } catch (\PDOException $e) {
                //连接不上写文件
                static::setMonologFile($config);
            }

        } else {
            static::setMonologFile($config);
        }
    }

    private static function setMonologFile(array $config)
    {
        $file  = 'runtime/log/' . date('Ym') . '/' . date('d') . '.log';
        $log   = new Logger('APP');
        $level = $config['app_debug'] ? Logger::DEBUG : Logger::INFO;
        //触发此处理程序的最小日志记录级别为INFO
        if (is_writable($file)) {
            $log->pushHandler(new StreamHandler($file, $level));
        } else {
            $log->pushHandler(new NullHandler());
        }
        static::$logger = $log;
    }

    /**
     * 调用未定义方法
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $instance = static::$logger;
        $result   = call_user_func_array(array($instance, $method), $args);
        return $result;
    }

    /**
     * 调用未定义静态方法
     * @param  [type] $method [description]
     * @param  [type] $args   [description]
     * @return [type]         [description]
     */
    public static function __callStatic($method, $args)
    {
        static::init();
        $instance = static::$logger;
        $result   = call_user_func_array(array($instance, $method), $args);
        return $result;
    }

}
