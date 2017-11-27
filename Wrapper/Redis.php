<?php
namespace Wrapper\Redis;

/**
 * Class Redis
 * @package Wrapper\Redis
 * @author Fea
 */
class Redis extends \Redis
{
    /** @var string host */
    protected $_host = '/var/run/redis/redis.sock';

    /** @var int port (default: 6379, socket: 0) */
    protected $_port = 0;

    /** @var int timeout */
    protected $_timeout= 0;

    /** @var int expire */
    protected $_expire_time = 0;

    /** @var int persistent_id */
    protected $_persistent_id = 'cache';

    /** @var int serializer */
    protected $_opt_serializer_default = Redis::SERIALIZER_PHP;

    /** @var int $_db_no */
    protected $_db_no = 0;

    /** @var int $_cache_prefix */
    protected $_cache_prefix = '';

    /** @var int $_current_db_no */
    private $_current_db_no = 0;

    public function __construct()
    {
        $this->pconnect($this->_host, $this->_port, $this->_timeout, $this->_persistent_id);
        $this->setOption(Redis::OPT_PREFIX, $this->_cache_prefix);
    }

    /**
     * DB変更
     * @param int $db_no
     * @return void
     */
    public function changeDb($db_no = 0)
    {
        if ($db_no != $this->_current_db_no && parent::select($db_no)) {
            $this->_current_db_no = $db_no;
        }
    }

    /**
     * キャッシュ削除
     * @param int $db_no
     * @return void
     */
    public function flushDb($db_no = 0)
    {
        if ($this->isConnect()) {
            parent::select($db_no);
            parent::flushDb();
            if ($this->_current_db_no !== $db_no) {
                parent::select($this->_current_db_no);
            }
        }
    }

    /**
     * 接続チェック
     * @return bool
     */
    private function isConnect() {
        $check = true;
        try {
            parent::ping();
        } catch (\RedisException $e) {
            $check = false;
        }
        return $check;
    }

    /**
     * @param string $host
     * @param int $port
     * @param float $timeout
     * @param string $persistent_id
     * @return void
     */
    public function pconnect($host, $port = 6379, $timeout = 0.0, $persistent_id = null)
    {
        if (!$this->isConnect()) {
            if ($port === 0) {
                parent::pconnect($host);
            } else {
                parent::pconnect($host, $port, $timeout, $persistent_id);
            }
            parent::setOption(Redis::OPT_SERIALIZER, $this->_opt_serializer_default);
        }
    }

    /**
     * @param array $keys
     * @return array
     */
    public function mget(array $keys)
    {
        $this->changeDb($this->_db_no);
        $result = [];
        $values = parent::mget($keys);
        $count = count($keys);
        $vcount = count($values);
        if ($count == $vcount) {
            $check = true;
            $tmp = [];
            for ($i=0; $i<$count; $i++) {
                if (empty($values[$i])) {
                    $check = false;
                    break;
                }
                $tmp[$keys[$i]] = $values[$i];
            }
            if ($check) {
                $result = $tmp;
            }
        }
        return $result;
    }

    /**
     * @param array $array
     * @return void
     */
    public function mset(array $array)
    {
        $this->changeDb($this->_db_no);
        if ($this->_expire_time > 0) {
            /** @var Redis $multi */
            $multi = parent::multi(Redis::PIPELINE);
            foreach ($array as $k => $v) {
                $multi->setEx($k, $this->_expire_time, $v);
            }
            $multi->exec();
        } else {
            parent::mset($array);
        }
    }

    /**
     * @param string $key
     * @return void
     */
    public function delAll($key = '')
    {
        $this->changeDb($this->_db_no);
        if (!empty($key)) {
            $key .= ':';
        }
        parent::del(parent::keys($key.'*'));
    }

    /**
     * @param string $key
     * @return array
     */
    public function hGetAll($key)
    {
        $this->changeDb($this->_db_no);
        return parent::hGetAll($key);
    }

    /**
     * @param string $key
     * @param array $keys
     * @return array
     */
    public function hMGet($key, $keys)
    {
        $result = [];
        $this->changeDb($this->_db_no);
        $values = parent::hMGet($key, $keys);
        $count = count($keys);
        $vcount = count($values);
        if ($count == $vcount) {
            $check = true;
            $tmp = [];
            for ($i=0; $i<$count; $i++) {
                if (empty($values[$i])) {
                    $check = false;
                    break;
                }
                $tmp[$keys[$i]] = $values[$i];
            }
            if ($check) {
                $result = $tmp;
            }
        }
        return $result;
    }

    /**
     * @param string $key
     * @param array $filed
     * @return void
     */
    public function hMSet($key, $filed)
    {
        $this->changeDb($this->_db_no);
        parent::hMSet($key, $filed);
        if ($this->_expire_time > 0) {
            parent::expire($key, $this->_expire_time);
        }
    }

    /**
     * @param string $key
     * @param string $hashKey1
     * @param string $hashKey2
     * @param string $hashKeyN
     * @return void
     */
    public function hDel($key, $hashKey1, $hashKey2 = null, $hashKeyN = null)
    {
        $this->changeDb($this->_db_no);
        parent::hDel($key, $hashKey1);
    }

    /**
     * @param string $key
     * @param array $keys
     * @return void
     */
    public function hMDel($key, $keys)
    {
        $this->changeDb($this->_db_no);
        /** @var Redis $multi */
        $multi = parent::multi(Redis::PIPELINE);
        foreach ($keys as $k) {
            $multi->hDel($key, $k);
        }
        $multi->exec();
    }

    /**
     * @param string $key
     * @return void
     */
    public function hDelAll($key = '')
    {
        $this->changeDb($this->_db_no);
        if (!empty($key)) {
            $key .= ':';
        }
        $keys = parent::hKeys($key.'*');
        /** @var Redis $multi */
        $multi = parent::multi(Redis::PIPELINE);
        foreach ($keys as $k) {
            $multi->hDel($key, $k);
        }
        $multi->exec();
    }
}