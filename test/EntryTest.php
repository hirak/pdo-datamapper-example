<?php
/**
 * Entryクラス自体のテスト
 * @group model
 */
class EntryTest extends PHPUnit_Framework_Testcase
{
    /**
     * @test
     */
    function 各フィールドの型のチェック() {
        $entry = new Entry;

        //違う型を投入してもキャストされることを確認
        $entry->entryId = '123';
        $this->assertSame(123, $entry->entryId);

        $entry->title = 123;
        $this->assertSame('123', $entry->title);

        $entry->content = 123;
        $this->assertSame('123', $entry->content);

        $entry->author = 123;
        $this->assertSame('123', $entry->author);

        $entry->published = '2012-01-01';
        $this->assertInstanceOf('DateTime', $entry->published);
    }

    /**
     * @test
     */
    function 妥当性検証() {
        $e = new Entry;

        $e->title = str_pad('', 100, 'a');
        $e->author = str_pad('', 100, 'b');
    }
}
