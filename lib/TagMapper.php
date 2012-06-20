<?php
class TagMapper extends DataMapper
{
    const MODEL_CLASS = 'Tag';

    // ------------ 更新系クエリ ------------
    function insert(Entry $entry, $tag)
    {
        $pdo = $this->_pdo;
        $modelClass = self::MODEL_CLASS;

        $stmt = $pdo->prepare('
            INSERT OR IGNORE
              INTO Tag(tag, entryId)
            VALUES (?, ?)
        ');
        $stmt->bindParam(1, $tagtext, PDO::PARAM_STR);
        $stmt->bindParam(2, $entryId, PDO::PARAM_INT);

        $entryId = $entry->entryId;

        if (! is_array($tag)) {
            $tag = array($tag);
        }

        foreach ($tag as $t) {
            if (! $t instanceof $modelClass) {
                throw new InvalidArgumentException;
            }
            $tagtext = $t->tag;
            $stmt->execute();
        }
    }

    //updateはない

    /**
     * entryから紐づくタグを削除する。
     * tagはTagインスタンスか、もしくはTagインスタンスの配列でもOK
     */
    function delete(Entry $entry, $tag)
    {
        $pdo = $this->_pdo;
        $modelClass = self::MODEL_CLASS;

        $stmt = $pdo->prepare('
            DELETE FROM Tag
             WHERE tag = ?
               AND entryId = ?
        ');
        $stmt->bindParam(1, $tagtext, PDO::PARAM_STR);
        $stmt->bindParam(2, $entryId, PDO::PARAM_INT);

        $entryId = $entry->entryId;

        if (! is_array($tag)) {
            $tag = array($tag);
        }

        foreach ($tag as $t) {
            if (! $t instanceof $modelClass) {
                throw new InvalidArgumentException;
            }
            $tagtext = $t->tag;
            $stmt->execute();
        }
    }
}
