<?php

namespace WP_CLI\SqlTransform\Tests;

use WP_CLI\SqlTransform\Parser;
use WP_CLI\SqlTransform\ParserFactory;
use \WP_CLI\SqlTransform\Sql;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class ParserFactoryTest extends TestCase
{
    /**
     * @covers \WP_CLI\SqlTransform\ParserFactory::create
     */
    public function testCreateMySQL()
    {
        $parser = ParserFactory::create( Sql\Dialect::MYSQL );
        self::assertInstanceOf( Parser\MySQL::class, $parser );
    }

    /**
     * @covers \WP_CLI\SqlTransform\ParserFactory::create
     */
    public function testCreateSQLite()
    {
        $parser = ParserFactory::create( Sql\Dialect::SQLITE );
        self::assertInstanceOf( Parser\SQLite::class, $parser );
    }

    /**
     * @covers \WP_CLI\SqlTransform\ParserFactory::create
     */
    public function testCreateThrowsExceptionOnUnsupportedDialect()
    {
        $this->expectException( \InvalidArgumentException::class );
        $this->expectExceptionMessage( 'Unsupported SQL dialect: unsupported' );
        ParserFactory::create( 'unsupported' );
    }

    /**
     * @covers \WP_CLI\SqlTransform\ParserFactory::create
     */
    public function testCreateThrowsExceptionOnUnimplementedDialect()
    {
        $this->expectException( \RuntimeException::class );
        $this->expectExceptionMessage( 'Parser not implemented yet for dialect: mariadb' );
        ParserFactory::create( 'mariadb' );
    }
}