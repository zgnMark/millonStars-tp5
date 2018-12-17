<?php
/**
 * @Author     SuJun (351699382@qq.com)
 * @time       2017-12-12
 * @link       https://github.com
 * @copyright  Copyright (c) 2017
 */
namespace app\vendor\cache;

use app\vendor\cache\RedisClient;

class Cache
{

    protected $cache;

    public function __construct($config)
    {
        $this->init($config);
    }

    public function init($config)
    {
        $this->cache = new RedisClient(['ip' => '127.0.0.1', 'port' => '6379', 'passwd' => '']);
    }

    /**
     * 仿session,设置
     * @param [type] $key [description]
     * @param [type] $val [description]
     */
    public function setSession($token, $key, $val)
    {
        $data = $this->cache->get($token);
        if ($data === false) {
            $data = [];
        } else {
            $data = unserialize($data);
        }
        $data[$key]         = $val;
        $data['updateTime'] = time();
        return $this->cache->set($token, serialize($data));
    }

    public function getSession($token, $key)
    {
        $data = $this->cache->get($token);
        if ($data === false) {
            return null;
        }
        $data = unserialize($data);
        //如果过期返回空,且30分钟
        if (isset($data[$key]) && ((time() - $data['updateTime']) <= 1800)) {
            //设置更新时间
            $data['updateTime'] = time();
            $this->cache->set($token, serialize($data));
            return $data[$key];
        } else {
            return null;
        }
    }

    /**
     * 设置缓存
     */
    public function set($key, $val)
    {
        return $this->cache->set($key, $val);
    }

    /**
     * 获取缓存
     * @return [type] [description]
     */
    public function get($key)
    {
        return $this->cache->get($key);
    }

}
