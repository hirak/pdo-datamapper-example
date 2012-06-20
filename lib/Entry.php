<?php
/**
 * ブログ記事一つを表すモデル
 *
 *
 */
class Entry extends DataModel
{
    protected static $_schema = array(
        'entryId'   => parent::INTEGER
      , 'author'    => parent::STRING
      , 'title'     => parent::STRING
      , 'content'   => parent::STRING
      , 'published' => parent::DATETIME
    );

    function isValid()
    {
        // authorは100文字まで、必須
        $val = $this->author;
        if (empty($val) || !mb_check_encoding($val) || mb_strlen($val) > 100) {
            return false;
        }

        // titleは100文字まで、必須
        $val = $this->title;
        if (empty($val) || !mb_check_encoding($val) || mb_strlen($val) > 100) {
            return false;
        }

        // contentは10000字まで、必須
        $val = $this->content;
        if (empty($val) || !mb_check_encoding($val) || mb_strlen($val) > 10000) {
            return false;
        }

        // publishedは型があっていれば問題ない
        $val = $this->published;
        if (empty($val)) {
            return false;
        }

        return true;
    }
}
