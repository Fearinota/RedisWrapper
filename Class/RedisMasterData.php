<?php
namespace Data\Redis;

include_once "../Wrapper/Redis.php";

/**
 * Class RedisMasterData
 * @package Data\Redis
 * @author Fea
 */
class RedisMasterData extends \Wrapper\Redis\Redis
{
    /** @var int expire */
    protected $_expire_time = 0;

    /** @var int $_db_no */
    protected $_db_no = 1;

    /** @var int $_cache_prefix */
    protected $_cache_prefix = 'MASTER';
}