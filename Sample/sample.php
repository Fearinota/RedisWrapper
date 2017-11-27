<?php
include_once "../Wrapper/Redis.php";
include_once "../Class/RedisMasterData.php";
include_once "../Batch/Redis.php";

use Data\Redis\RedisMasterData;
use Batch\Redis as BatchRedis;

/**
 * サンプルプログラム
 * PHP+MySQL+Redis
 * @author Fea
 */

$db_host = 'localhost';
$db_name = 'redis_test';
$table_name = 'cards';
$test_data_num = 10000;
$get_num = 20;
$get_item_num = 100;
$precision = 5;

/** DB接続 */
$pdo = null;
$dsn = 'mysql:dbname='.$db_name.';host='.$db_host;
$user = 'root';
try {
    $pdo = new \PDO($dsn, $user);
} catch (\PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

/** テストデータ作成 */
$sql = "TRANCATE TABLE ".$table_name;
$pdo->query($sql);
for ($i=1; $i<=$test_data_num; $i++) {
    $sql = "INSERT INTO ".$table_name." VALUES (".$i.", 'name".$i."', 1, 1, ".$i.", ".$i.", ".$i.", ".$i.", ".$i.", ".$i.", ".$i.", ".$i.", ".$i.", now(), now())";
    $pdo->query($sql);
}

/** キャッシュ作成 */
echo "キャッシュ作成".PHP_EOL;
$start_time = microtime(true);
$batch = new BatchRedis($pdo);
$batch->setMasterData($table_name);
$diff_time = round(microtime(true) - $start_time, $precision);
echo "{$diff_time}".PHP_EOL;

/** キャッシュ取得テスト */
echo "キャッシュ取得テスト".PHP_EOL;
$redis = new RedisMasterData();
$start_time = microtime(true);
for ($i=0; $i<$get_num; $i++) {
    $keys = [];
    for ($j=0; $j<$get_item_num; $j++) {
        $keys[] = mt_rand(1, $test_data_num);
    }
    $get_start_time = microtime(true);
    $data = $redis->hMGet($table_name, $keys);
//    echo json_encode($data).PHP_EOL;
    $diff_time = round(microtime(true) - $get_start_time, $precision);
    echo ($i+1).": {$diff_time}".PHP_EOL;
}
$diff_time = round(microtime(true) - $start_time, $precision);
echo "{$diff_time}".PHP_EOL;