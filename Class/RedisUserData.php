<?php
namespace Data\Redis;

include_once "../Wrapper/Redis.php";

/**
 * Class RedisUserData
 * @package Data\Redis
 * @author Fea
 */
class RedisUserData extends \Wrapper\Redis\Redis
{
    /** @var int expire */
    protected $_expire_time = 300;

    /** @var int $_db_no */
    protected $_db_no = 2;

    /** @var int $_cache_prefix */
    protected $_cache_prefix = 'USER';
}