<?php

namespace WP_CLI\SqlTransform\Tests\Sql\Statement;

use WP_CLI\SqlTransform\Sql;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class GenericTest extends TestCase
{
    /**
     * @covers \WP_CLI\SqlTransform\Sql\Statement\Generic::__construct
     */
    public function testInstantiation() {
        $statement = new Sql\Statement\Generic( 'SELECT * FROM `wp_posts`;' );
        self::assertInstanceOf( Sql\Statement::class, $statement );
        self::assertInstanceOf( Sql\Statement\Generic::class, $statement );
    }

    /**
     * @covers \WP_CLI\SqlTransform\Sql\Statement\Generic::__construct
     * @covers \WP_CLI\SqlTransform\Sql\Statement\Generic::extract_keyword
     * @covers \WP_CLI\SqlTransform\Sql\Statement\Generic::get_keyword
     */
    public function testGetKeyword() {
        $statement = new Sql\Statement\Generic( 'SELECT * FROM `wp_posts`;' );
        self::assertEquals( 'SELECT', $statement->get_keyword() );
    }

    /**
     * @covers \WP_CLI\SqlTransform\Sql\Statement\Generic::__construct
     * @covers \WP_CLI\SqlTransform\Sql\Statement\Generic::extract_qualifiers
     * @covers \WP_CLI\SqlTransform\Sql\Statement\Generic::get_qualifiers
     */
    public function testGetQualifiers() {
        $statement = new Sql\Statement\Generic( 'SELECT * FROM `wp_posts`;' );
        self::assertEquals( [ '*', 'FROM', '`wp_posts`' ], $statement->get_qualifiers() );
    }
}