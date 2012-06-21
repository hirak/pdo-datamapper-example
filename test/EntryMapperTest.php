<?php
/**
 * EntryMapperのテスト
 *
 */
class EntryMapperTest extends PHPUnit_Framework_Testcase
{
    static $pdo;

    //始めに、テスト用のDBに接続しておき、使いまわす。
    static function setUpBeforeClass()
    {
        $pdo = self::$pdo = getPDO('test');
    }

    //テストの度にDBをクリーンな状態に戻す。
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
    function entryをDBに保存する。主キーはDBが算出してentryにセットされる() {
        $emapper = new EntryMapper(self::$pdo);

        $entry = new Entry;
        $entry->author = 'Mr. Dummy';
        $entry->title  = 'Hello';
        $entry->content = 'Hello, World!';
        $entry->published = new DateTime;

        $emapper->insert($entry);
        $this->assertArrayHasKey('entryId', $entry->toArray());
    }

    /**
     * @test
     */
    function entryは複数まとめて保存可能() {
        $emapper = new EntryMapper(self::$pdo);

        $entry = new Entry;
        $entry->author = 'Mr. Dummy';
        $entry->title = 'Hello';
        $entry->content = 'Hello, World!';
        $entry->published = new DateTime;

        $entry2 = new Entry;
        $entry2->author = 'Mr. Dummy';
        $entry2->title = 'Hello2';
        $entry2->content = 'Hello, World!2';
        $entry2->published = new DateTime;

        $emapper->insert(array($entry, $entry2));
        $this->assertArrayHasKey('entryId', $entry->toArray());
        $this->assertArrayHasKey('entryId', $entry2->toArray());
    }

    /**
     * @test
     */
    function entryを更新する() {
        $emapper = new EntryMapper(self::$pdo);

        $entry = new Entry;
        $entry->author = 'Mr. Dummy';
        $entry->title = 'Hello';
        $entry->content = 'Hello, World!';
        $entry->published = new DateTime;

        $emapper->insert($entry);
        $this->assertArrayHasKey('entryId', $entry->toArray());

        $entry->title = 'HelloUpdated';
        $emapper->update($entry);

        //DBに保存されたか確かめるため、fetchしなおす
        $entry = $emapper->find($entry->entryId);
        $this->assertSame('HelloUpdated', $entry->title);
    }

    /**
     * @test
     */
    function entryを削除する() {
        $entry = new Entry;
        $entry->author = 'Mr. Dummy';
        $entry->title = 'Hello';
        $entry->content = 'Hello World';
        $entry->published = new DateTime;

        $emapper = new EntryMapper(self::$pdo);

        $emapper->insert($entry);
        $emapper->delete($entry);
        $entries = $emapper->findAll()->fetchAll();

        $this->assertEmpty($entries);
    }
}
