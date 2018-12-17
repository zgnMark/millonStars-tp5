<?php
namespace app\vendor\cache;

use \Redis;

class RedisClient
{

    protected $redis; // redis对象
    protected $ip     = '127.0.0.1'; // redis服务器ip地址
    protected $port   = 6379; // redis服务器端口
    protected $passwd = ''; // redis密码

    public function __construct($config = array())
    {
        $this->redis = new Redis();

        if ($config) {
            $this->ip   = $config['ip'];
            $this->port = $config['port'];
            if (isset($config['passwd'])) {
                $this->passwd = $config['passwd'];
            }
        }

        $state = $this->redis->connect($this->ip, $this->port);
        if ($state == false) {
            die('redis connect failed');
        }
        if ($this->passwd) {
            $this->redis->auth($this->passwd);
        }

        $this->redis->select(0); // 选择0号数据库
        $this->redis->setOption(Redis::OPT_PREFIX, 'gw:'); // 设置键名的前缀
    }

    /**
     * 设置键值对（如果key已经存在，则进行覆盖）
     * @param unknown $key
     * @param unknown $value
     * 返回布尔值
     */
    public function set($key, $value)
    {
        return $this->redis->set($key, $value);
    }

    /**
     * @param unknown $key
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * 判断某个key是否存在
     * @param unknown $key
     * 返回布尔值
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    /**
     * 删除某个key
     * @param unknown $key
     * 返回删除的key的数目
     */
    public function delete($key)
    {
        return $this->redis->delete($key);
    }

    /**
     * 清空当前数据库中的所有key
     * 总是返回true
     */
    public function flushDb()
    {
        return $this->redis->flushDb();
    }

    /**
     * 清空所有数据库中的所有key
     * 总是返回true
     */
    public function flushAll()
    {
        return $this->redis->flushAll();
    }

}
