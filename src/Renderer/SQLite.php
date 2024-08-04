<?php

namespace WP_CLI\SqlTransform\Renderer;

use WP_CLI\SqlTransform\Renderer;
use WP_CLI\SqlTransform\Sql;

final class SQLite implements Renderer {

    /**
     * Render a collection of SQL statements into a single string.
     *
     * @param Sql\Statement ...$statements The SQL statements to render.
     * @return string The rendered SQL statements.
     */
    public function render( Sql\Statement ...$statements ): string {
        $sql = '';
        foreach ( $statements as $statement ) {
            $statement_class = get_class( $statement );
            switch ( $statement_class ) {
                case Sql\Statement\Generic::class:
                    /** @var Sql\Statement\Generic $statement */
                    $keyword = $statement->get_keyword();
                    $qualifiers = implode( ' ', $statement->get_qualifiers() );
                    $sql .= "{$keyword} {$qualifiers};\n";
                    break;
                default:
                    $sql .= "-- unrenderable statement: {$statement_class}\n";
            }
        }
        return $sql;
    }
}