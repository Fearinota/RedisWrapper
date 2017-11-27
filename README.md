# RedisWrapper
PhpRedisのラッパークラスです。
用途別に設定切り替えしやすいようにしました。

## Description

サンプルプログラムはコマンドラインで動作します。
事前にテーブル作成しておいてください。
MySQL以外のDBで試す場合はDSN等の書き換えも必要です。

```
cd Sample/
php ./sample.php
```

## Requirement
php7.0以上
phpredis/phpredis

## Directory Structures

```
RedisWrapper
|-- Batch/    バッチ処理サンプル
|-- Sql/      サンプル用テーブル作成DDL
|-- Sample/   サンプルスクリプト
|-- Class/    用途別のサンプルクラス
`-- Wrapper/  本体
```

## Install

```
git clone https://github.com/Fearinota/RedisWrapper.git
```

## Author

[Fea](https://github.com/fearinota)