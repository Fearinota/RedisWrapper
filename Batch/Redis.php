<?php
namespace Batch;

include_once "../Wrapper/Redis.php";
include_once "../Class/RedisMasterData.php";

/**
 * Class Redis
 * @package Batch
 * @author Fea
 */
class Redis
{
    /** @var \PDO $_pdo */
    private $_pdo = null;

    public function __construct($pdo)
    {
        $this->_pdo = $pdo;
    }

    /**
     * マスターデータキャッシュ
     *
     * @return void
     */
    public function setMasterData($table_name)
    {
        $redis = new \Data\Redis\RedisMasterData();
        $keys = [];
        $sql = 'SELECT * FROM '.$table_name;
        $rows = $this->_pdo->query($sql,\PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $keys[$row['id']] = $row;
        }
        $redis->hMSet($table_name, $keys);
    }

    /**
     * カードマスターをキャッシュ
     * @return void
     */
    public function setCardMasterData()
    {
        self::setMasterData('cards');
    }
}