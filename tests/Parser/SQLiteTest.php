<?php

namespace WP_CLI\SqlTransform\Tests\Parser;

use WP_CLI\SqlTransform\File;
use WP_CLI\SqlTransform\Parser;
use WP_CLI\SqlTransform\Sql;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class SQLiteTest extends TestCase
{
    /**
     * @covers \WP_CLI\SqlTransform\Parser\SQLite::parse
     */
    public function testParseSingleStatement()
    {
        $parser     = new Parser\SQLite();
        $sql_file   = new File\Memory( 'SELECT * FROM `tableA`;' );
        $statements = $parser->parse( $sql_file );
        self::assertCount( 1, $statements );
        self::assertInstanceOf( \WP_CLI\SqlTransform\Sql\Statement::class, $statements[0] );
    }

    /**
     * @covers \WP_CLI\SqlTransform\Parser\SQLite::parse
     */
    public function testParseMultipleStatements()
    {
        $parser = new Parser\SQLite();
        $sql_file   = new File\Memory( 'CREATE `tableA`;DROP `tableB`;UPDATE `tableC`;' );
        /** @var array<Sql\Statement\Generic> $statements */
        $statements = $parser->parse( $sql_file );
        self::assertCount( 3, $statements );
        self::assertInstanceOf( Sql\Statement::class, $statements[0] );
        self::assertInstanceOf( Sql\Statement\Generic::class, $statements[0] );
        self::assertEquals( 'CREATE', $statements[0]->get_keyword() );
        self::assertEquals( [ '`tableA`' ], $statements[0]->get_qualifiers() );
        self::assertInstanceOf( Sql\Statement::class, $statements[1] );
        self::assertInstanceOf( Sql\Statement\Generic::class, $statements[1] );
        self::assertEquals( 'DROP', $statements[1]->get_keyword() );
        self::assertEquals( [ '`tableB`' ], $statements[1]->get_qualifiers() );
        self::assertInstanceOf( Sql\Statement::class, $statements[2] );
        self::assertInstanceOf( Sql\Statement\Generic::class, $statements[2] );
        self::assertEquals( 'UPDATE', $statements[2]->get_keyword() );
        self::assertEquals( [ '`tableC`' ], $statements[2]->get_qualifiers() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\Parser\SQLite::parse
     */
    public function testParseMultipleStatementsWithExtraWhitespace()
    {
        $parser     = new Parser\SQLite();
        $sql_file   = new File\Memory(
            '      CREATE   `tableA`  ;  DROP     `tableB`  ; ' . PHP_EOL . '  UPDATE   `tableC`  ;   '
        );
        /** @var array<Sql\Statement\Generic> $statements */
        $statements = $parser->parse( $sql_file );
        self::assertCount( 3, $statements );
        self::assertInstanceOf( Sql\Statement::class, $statements[0] );
        self::assertInstanceOf( Sql\Statement\Generic::class, $statements[0] );
        self::assertEquals( 'CREATE', $statements[0]->get_keyword() );
        self::assertEquals( [ '`tableA`' ], $statements[0]->get_qualifiers() );
        self::assertInstanceOf( Sql\Statement::class, $statements[1] );
        self::assertInstanceOf( Sql\Statement\Generic::class, $statements[1] );
        self::assertEquals( 'DROP', $statements[1]->get_keyword() );
        self::assertEquals( [ '`tableB`' ], $statements[1]->get_qualifiers() );
        self::assertInstanceOf( Sql\Statement::class, $statements[2] );
        self::assertInstanceOf( Sql\Statement\Generic::class, $statements[2] );
        self::assertEquals( 'UPDATE', $statements[2]->get_keyword() );
        self::assertEquals( [ '`tableC`' ], $statements[2]->get_qualifiers() );
    }
}