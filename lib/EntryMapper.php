<?php

class EntryMapper extends DataMapper
{
    const MODEL_CLASS = 'Entry';

    // ------------- 更新系クエリ -----------------

    /**
     * Model\Entryか、Model\Entryの配列を引数に取り、全部DBにinsertします。
     *
     */
    function insert($data) {
        $pdo = $this->_pdo;
        $modelClass = self::MODEL_CLASS;

        $stmt = $pdo->prepare('
            INSERT INTO Entry(author, title, content, updated)
            VALUES (?, ?, ?, ?)
        ');
        $stmt->bindParam(1, $author,  PDO::PARAM_STR);
        $stmt->bindParam(2, $title,   PDO::PARAM_STR);
        $stmt->bindParam(3, $content, PDO::PARAM_STR);
        $stmt->bindParam(4, $updated, PDO::PARAM_STR);

        if (! is_array($data)) {
            $data = array($data);
        }
        foreach ($data as $row) {
            if (! $row instanceof $modelClass || ! $row->isValid()) {
                throw new InvalidArgumentException;
            }
            $author  = $row->author;
            $title   = $row->title;
            $content = $row->content;
            $updated = $row->updated->format('c');
            $stmt->execute();

            //autoincrementな主キーをオブジェクト側へ反映
            $row->entryId = $pdo->lastInsertId();
        }
    }

    function update($data)
    {
        $modelClass = self::MODEL_CLASS;

        $stmt = $this->_pdo->prepare('
            UPDATE Entry
               SET author = ?
                 , title = ?
                 , content = ?
                 , updated = ?
             WHERE entryId = ?
        ');
        $stmt->bindParam(1, $author,  PDO::PARAM_STR);
        $stmt->bindParam(2, $title,   PDO::PARAM_STR);
        $stmt->bindParam(3, $content, PDO::PARAM_STR);
        $stmt->bindParam(4, $updated, PDO::PARAM_STR);
        $stmt->bindParam(5, $entryId, PDO::PARAM_INT);

        if (! is_array($data)) {
            $data = array($data);
        }
        foreach ($data as $row) {
            if (! $row instanceof $modelClass || ! $row->isValid()) {
                throw new InvalidArgumentException;
            }
            $entryId = $row->entryId;
            $author  = $row->author;
            $title   = $row->title;
            $content = $row->content;
            $updated = $row->updated->format('c');
            $stmt->execute();
        }
    }

    function delete($data)
    {
        $modelClass = self::MODEL_CLASS;

        $stmt = $this->_pdo->prepare('
            DELETE FROM Entry
             WHERE entryId = ?
        ');
        $stmt->bindParam(1, $entryId, PDO::PARAM_INT);

        if (! is_array($data)) {
            $data = array($data);
        }
        foreach ($data as $row) {
            if (! $row instanceof $modelClass) {
                throw new InvalidArgumentException;
            }
            $entryId = $row->entryId;
            $stmt->execute();
        }
    }

    //------------- 参照系クエリ ----------------

    function find($entryId)
    {
        $stmt = $this->_pdo->prepare('
            SELECT *
              FROM Entry
             WHERE entryId = ?
        ');
        $stmt->bindParam(1, $entryId, PDO::PARAM_INT);
        $stmt->execute();

        $this->_decorate($stmt);
        return $stmt->fetch();
    }

    function findAll()
    {
        $stmt = $this->_pdo->query('
            SELECT *
              FROM Entry
        ');
        return $this->_decorate($stmt);
    }

    // TagかTagの配列から関連するEntryを引く
    // 配列で渡すとORとして取得
    function findByTag($tag)
    {
        $pdo = $this->_pdo;

        if (!is_array($tag)) {
            $tag = array($tag);
        }

        // INを使いたいのでプリペアドステートメントを使わず
        // 手動でSQLを組み立てる
        $taglist = array();
        foreach ($tag as $t) {
            if (! $t instanceof Tag || ! $t->isValid()) {
                throw new InvalidArgumentException;
            }
            $taglist[] = $pdo->quote($t->tag, PDO::PARAM_STR);
        }
        $stmt = $pdo->query('
            SELECT DISTINCT Entry.*
              FROM Entry NATURAL JOIN Tag
             WHERE Tag.tag IN (' . implode(',', $taglist) .')
        ');

        return $this->_decorate($stmt);
    }
}
