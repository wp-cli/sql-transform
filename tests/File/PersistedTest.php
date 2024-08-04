<?php

namespace WP_CLI\SqlTransform\Tests\File;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use WP_CLI\SqlTransform\File;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class PersistedTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $filesystem;

    public function setUp(): void
    {
        parent::setUp();
        $directory = [
            'empty-file.sql'     => '',
            'non-empty-file.sql' => "SELECT * FROM `wp_posts`;\n",
            'dump.sql'           => "SELECT * FROM `wp_posts`;\n"
                                    . "INSERT INTO `wp_users` VALUES (1, 'admin');\n"
                                    . "DROP TABLE `wp_options`;\n",
        ];
        $this->filesystem = vfsStream::setup( 'root', '444', $directory );

    }
    /**
     * @covers \WP_CLI\SqlTransform\File\Persisted::__construct
     * @covers \WP_CLI\SqlTransform\File\Persisted::has_next_statement
     * @covers \WP_CLI\SqlTransform\File\Persisted::__destruct
     */
    public function testHasNextStatementReturnsFalseOnEmptyFile()
    {
        $file = new File\Persisted( $this->filesystem->url() . '/empty-file.sql' );
        self::assertFalse( $file->has_next_statement() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\File\Persisted::__construct
     * @covers \WP_CLI\SqlTransform\File\Persisted::has_next_statement
     * @covers \WP_CLI\SqlTransform\File\Persisted::__destruct
     */
    public function testHasNextStatementReturnsTrueOnNonEmptyFile()
    {
        $file = new File\Persisted( $this->filesystem->url() . '/non-empty-file.sql' );
        self::assertTrue( $file->has_next_statement() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\File\Persisted::__construct
     * @covers \WP_CLI\SqlTransform\File\Persisted::get_next_statement
     * @covers \WP_CLI\SqlTransform\File\Persisted::__destruct
     */
    public function testGetNextStatementReturnsEmptyStatementOnEmptyFile()
    {
        $file = new File\Persisted( $this->filesystem->url() . '/empty-file.sql' );
        self::assertEquals( '', $file->get_next_statement() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\File\Persisted::__construct
     * @covers \WP_CLI\SqlTransform\File\Persisted::get_next_statement
     * @covers \WP_CLI\SqlTransform\File\Persisted::__destruct
     */
    public function testGetNextStatementReturnsNextStatementOnNonEmptyFile()
    {
        $file = new File\Persisted( $this->filesystem->url() . '/non-empty-file.sql' );
        self::assertEquals( "SELECT * FROM `wp_posts`;\n", $file->get_next_statement() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\File\Persisted::__construct
     * @covers \WP_CLI\SqlTransform\File\Persisted::has_next_statement
     * @covers \WP_CLI\SqlTransform\File\Persisted::get_next_statement
     * @covers \WP_CLI\SqlTransform\File\Persisted::__destruct
     */
    public function testHasNextStatementReturnsFalseWhenAtEndOfFile()
    {
        $file = new File\Persisted( $this->filesystem->url() . '/dump.sql' );
        self::assertTrue( $file->has_next_statement() );
        $file->get_next_statement();
        self::assertTrue( $file->has_next_statement() );
        $file->get_next_statement();
        self::assertTrue( $file->has_next_statement() );
        $file->get_next_statement();
        self::assertFalse( $file->has_next_statement() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\File\Persisted::__construct
     * @covers \WP_CLI\SqlTransform\File\Persisted::has_next_statement
     * @covers \WP_CLI\SqlTransform\File\Persisted::get_next_statement
     * @covers \WP_CLI\SqlTransform\File\Persisted::__destruct
     */
    public function testMultipleStatementsCanBeRetrieved()
    {
        $file = new File\Persisted( $this->filesystem->url() . '/dump.sql' );
        $statements = [];
        while ( $file->has_next_statement() ) {
            $statements[] = $file->get_next_statement();
        }
        self::assertCount( 3, $statements );
        self::assertEquals( [
            "SELECT * FROM `wp_posts`;\n",
            "INSERT INTO `wp_users` VALUES (1, 'admin');\n",
            "DROP TABLE `wp_options`;\n",
        ], $statements );
    }
}