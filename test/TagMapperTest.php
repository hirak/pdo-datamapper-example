<?php
/**
 * TagMapperのテスト
 *
 */

class TagMapperTest extends PHPUnit_Framework_Testcase
{
    static $pdo;

    static function setUpBeforeClass()
    {
        $pdo = self::$pdo = getPDO('test');
    }

    function setUp()
    {
        $pdo = self::$pdo;
        // DB clean up
        $pdo->beginTransaction();
        $pdo->query('DELETE FROM Entry');
        $pdo->query('DELETE FROM Tag');
        $pdo->commit();
    }

    /**
     * @test
     */
    function タグをentryに紐づけて保存()
    {
        $emapper = new EntryMapper(self::$pdo);
        $entry = new Entry;
        $entry->author = 'Mr. Dummy';
        $entry->title = 'Hello';
        $entry->content = 'Hello World';
        $entry->published = new DateTime;

        $emapper->insert($entry);

        $tmapper = new TagMapper(self::$pdo);
        $funny = new Tag('funny');
        $tmapper->insert($entry, $funny);

        $entries = $emapper->findByTag($funny);
        $this->assertSame($entry->entryId, $entries->fetch()->entryId, 'タグを保存できた');

        $tmapper->delete($entry, $funny);
        $entries = $emapper->findByTag($funny);
        $this->assertEmpty($entries->fetchAll(), 'タグを消したのでヒットしなくなる');
    }
}
