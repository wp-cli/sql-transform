<?php

namespace WP_CLI\SqlTransform\Tests\File;

use WP_CLI\SqlTransform\File;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class MemoryTest extends TestCase
{
    /**
     * @covers \WP_CLI\SqlTransform\File\Memory::__construct
     * @covers \WP_CLI\SqlTransform\File\Memory::has_next_statement
     */
    public function testHasNextStatementReturnsFalseOnEmptyFile()
    {
        $file = new File\Memory( '' );
        self::assertFalse( $file->has_next_statement() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\File\Memory::__construct
     * @covers \WP_CLI\SqlTransform\File\Memory::has_next_statement
     */
    public function testHasNextStatementReturnsTrueOnNonEmptyFile()
    {
        $sql = 'SELECT * FROM `wp_posts`;';
        $file = new File\Memory( $sql );
        self::assertTrue( $file->has_next_statement() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\File\Memory::__construct
     * @covers \WP_CLI\SqlTransform\File\Memory::has_next_statement
     * @covers \WP_CLI\SqlTransform\File\Memory::get_next_statement
     */
    public function testHasNextStatementReturnsFalseWhenAtEndOfFile()
    {
        $sql = 'SELECT * FROM `wp_posts`; SELECT * FROM `wp_users`; ';
        $file = new File\Memory( $sql );
        self::assertTrue( $file->has_next_statement() );
        $file->get_next_statement();
        self::assertTrue( $file->has_next_statement() );
        $file->get_next_statement();
        self::assertFalse( $file->has_next_statement() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\File\Memory::__construct
     * @covers \WP_CLI\SqlTransform\File\Memory::get_next_statement
     */
    public function testGetNextStatement()
    {
        $sql = 'SELECT * FROM `wp_posts`;';
        $file = new File\Memory( $sql );
        self::assertSame( $sql, $file->get_next_statement() );
    }
}