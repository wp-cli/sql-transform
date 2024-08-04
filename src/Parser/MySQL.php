<?php

namespace WP_CLI\SqlTransform\Parser;

use WP_CLI\SqlTransform\File;
use WP_CLI\SqlTransform\Parser;
use WP_CLI\SqlTransform\Sql;

final class MySQL implements Parser {

    /**
     * Parse a string of SQL statements into an array SQL statement objects.
     *
     * @param File $sql_file The string of SQL statements to parse.
     * @return array<Sql\Statement> The parsed SQL statements.
     */
    public function parse( File $sql_file ): array {
        while ( $sql_file->has_next_statement() ) {
            $statements[] = new Sql\Statement\Generic( $sql_file->get_next_statement() );
        }
        return $statements;
    }
    
    /**
     * Parse a single SQL statement into a SQL statement object.
     *
     * @param string $sql The SQL statement to parse.
     * @return Sql\Statement The parsed SQL statement.
     */
    public function parse_statement( string $sql ): Sql\Statement {
        return new Sql\Statement\Generic( $sql );
    }
}