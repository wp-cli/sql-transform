<?php

namespace WP_CLI\SqlTransform\Tests\Renderer;

use WP_CLI\SqlTransform\Renderer;
use WP_CLI\SqlTransform\Sql;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class SQLiteTest extends TestCase
{
    /**
     * @covers \WP_CLI\SqlTransform\Renderer\SQLite::render
     */
    public function testRenderWithSingleStatement()
    {
        $renderer = new Renderer\SQLite();
        $statement = new Sql\Statement\Generic( 'SELECT * FROM `tableA`;' );
        self::assertSame( "SELECT * FROM `tableA`;\n", $renderer->render( $statement ) );
    }

    /**
     * @covers \WP_CLI\SqlTransform\Renderer\SQLite::render
     */
    public function testRenderWithMultipleStatements()
    {
        $renderer = new Renderer\SQLite();
        $statements = [
            new Sql\Statement\Generic( 'CREATE `tableA`;' ),
            new Sql\Statement\Generic( 'DROP `tableB`;' ),
            new Sql\Statement\Generic( 'UPDATE `tableC`;' )
        ];
        $rendered = '';
        foreach ( $statements as $statement ) {
            $rendered .= $renderer->render( $statement );
        }
        self::assertSame( "CREATE `tableA`;\nDROP `tableB`;\nUPDATE `tableC`;\n", $rendered );
    }


    /**
     * @covers \WP_CLI\SqlTransform\Renderer\SQLite::render
     */
    public function testRenderWithMultipleStatementsAndExtraWhitespace()
    {
        $renderer = new Renderer\SQLite();
        $statements = [
            new Sql\Statement\Generic( "  CREATE    `tableA`;  \n\n " ),
            new Sql\Statement\Generic( " \n      DROP    `tableB`  ; " ),
            new Sql\Statement\Generic( " UPDATE `tableC`  ;  \n \n " )
        ];
        $rendered = '';
        foreach ( $statements as $statement ) {
            $rendered .= $renderer->render( $statement );
        }
        self::assertSame( "CREATE `tableA`;\nDROP `tableB`;\nUPDATE `tableC`;\n", $rendered );
    }

    /**
     * @covers \WP_CLI\SqlTransform\Renderer\SQLite::render
     */
    public function testRenderWithUnknownStatement()
    {
        $renderer = new Renderer\SQLite();
        $statement = new class implements Sql\Statement {};
        $rendered = $renderer->render( $statement );
        self::assertSame( "-- unrenderable statement: " . get_class( $statement ) . "\n", $rendered );
    }
}