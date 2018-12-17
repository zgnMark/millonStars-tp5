<?php
/**
 *           佛祖保佑       永无BUG
 *
 *                   _ooOoo_
 *                  o8888888o
 *                  88" . "88
 *                  (| -_- |)
 *                  O\  =  /O
 *               ____/`---'\____
 *             .'  \\|     |//  `.
 *            /  \\|||  :  |||//  \
 *           /  _||||| -:- |||||-  \
 *           |   | \\\  -  /// |   |
 *           | \_|  ''\---/''  |   |
 *           \  .-\__  `-`  ___/-. /
 *         ___`. .'  /--.--\  `. . __
 *      ."" '<  `.___\_<|>_/___.'  >'"".
 *     | | :  `- \`.;`\ _ /`;.`/ - ` : | |
 *     \  \ `-.   \_ __\ /__ _/   .-` /  /
 * ======`-.____`-.___\_____/___.-`____.-'======
 *                   `=---='
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-04
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\vendor\log;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use PDO;

/**
 * 日志写入数据库
 */
class PDOLogHandler extends AbstractProcessingHandler
{
    private $initialized = false;
    private $pdo;
    private $statement;

    public function __construct(PDO $pdo, $level = Logger::DEBUG, $bubble = true)
    {
        $this->pdo = $pdo;
        parent::__construct($level, $bubble);
    }

    protected function write(array $log)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        $this->statement->execute(array(
            'channel'     => $log['channel'],
            'level'       => $log['level_name'],
            'message'     => $log['formatted'],
            'create_time' => date('Y-m-d H:i:s'),
        ));
    }

    private function initialize()
    {
        $this->statement = $this->pdo->prepare(
            'INSERT INTO j_monolog (channel, level, message, create_time) VALUES (:channel, :level, :message, :create_time)'
        );

        $this->initialized = true;
    }
}
