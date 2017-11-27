<?php
namespace Data\Redis;

include_once "../Wrapper/Redis.php";

/**
 * Class RedisExternalUserData
 * @package Data\Redis
 * @author Fea
 */
class RedisExternalUserData extends \Wrapper\Redis\Redis
{
    /** @var string host */
    protected $_host = '';  //外部Redisのhost

    /** @var int expire */
    protected $_expire_time = 3600;

    /** @var int $_db_no */
    protected $_db_no = 2;

    /** @var int $_cache_prefix */
    protected $_cache_prefix = 'USER';
}