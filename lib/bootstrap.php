<?php
/**
 * bootstrap.php これらのサンプルプログラムを動かす際に必要になるもの
 *
 */

//ライブラリの読み込み基準パスを修正
set_include_path(
    __DIR__
  . PATH_SEPARATOR
  . get_include_path()
);

//簡単なオートローダーをセット
spl_autoload_register(function($c){
    include_once strtr($c, '\_', '//').'.php';
});

//functionはオートローダーが効かないので手動ロード
require_once 'functions.php';
