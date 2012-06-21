pdo-datamapper-example
======================

PDOの使い方のサンプルです。

## テストの動かし方

phpunitのインストールは省略…

dataディレクトリ内のexample_empty.dbは、SQLite3用の空っぽのDBファイルです。

test.dbの名前でSQLiteファイルを作って、
testディレクトリに移動して、
phpunitコマンドを実行するだけ！

$ cp data/example_empty.db data/test.db
$ cd test
$ phpunit
