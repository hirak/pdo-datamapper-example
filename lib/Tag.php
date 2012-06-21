<?php
/**
 * ブログにつけられるタグ一つを表すモデル
 *
 */
class Tag extends DataModel
{
    protected static $_schema = array(
        'tag'   => parent::STRING
    );

    //new Tag('おもしろ')など、コンストラクタで初期化できるようにする
    function __construct($default=null) {
        $this->tag = $default;
    }

    function __toString() {
        return (string) $this->tag;
    }

    function isValid()
    {
        $t = $this->tag;
        if (empty($t) || ! mb_check_encoding($t) || mb_strlen($t) > 100) {
           return false;
        }
        return true;
    }
}
