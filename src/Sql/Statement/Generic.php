<?php

namespace WP_CLI\SqlTransform\Sql\Statement;

use WP_CLI\SqlTransform\Sql\Statement;

final class Generic implements Statement {
    /**
     * @param string $keyword The main keyword defining the type of the SQL statement.
     */
    private $keyword;

    /**
     * @param array $words The qualifiers that make up the SQL statement.
     */
    private $qualifiers;

    public function __construct( string $sql ) {
        $sql = trim( $sql );
        $sql = rtrim( $sql, ';' );
        $this->keyword = $this->extract_keyword( $sql );
        $this->qualifiers = $this->extract_qualifiers( $sql );
    }

    /**
     * Extract the keyword that defines the type of the SQL statement.
     *
     * The keyword is the first word in the SQL statement, e.g. SELECT, INSERT, UPDATE, DELETE.
     * It is usually followed by one or more whitespace characters.
     *
     * @param string $sql The SQL statement.
     * @return string The keyword that defines the type of the SQL statement.
     */
    private function extract_keyword( string $sql ): string {
        $matches = [];
        $found = preg_match( '/^\s*(\S+)\s+/', $sql, $matches );
        return $found ? $matches[1] : '';
    }

    /**
     * Extract the qualifiers that make up the SQL statement.
     *
     * The qualifiers are the words that follow the keyword in the SQL statement.
     * They are usually separated by one ore more whitespace characters.
     *
     * @param string $sql The SQL statement.
     * @return array The qualifiers that make up the SQL statement.
     */
    private function extract_qualifiers( string $sql ): array {
        $qualifiers = [];
        $matches = [];
        $found = preg_match_all( '/\S+/', $sql, $matches );
        if ( $found ) {
            // The first match is the keyword, so we skip it.
            $qualifiers = array_slice( $matches[0], 1 );
        }
        return $qualifiers;
    }

    /**
     * Get the keyword that defines the type of the SQL statement.
     *
     * @return string The keyword that defines the type of the SQL statement.
     */
    public function get_keyword( $adapt_case = true ): string {
        return $adapt_case ? mb_strtoupper( $this->keyword ) : $this->keyword;
    }

    /**
     * Get the qualifiers that make up the SQL statement.
     *
     * @return array The qualifiers that make up the SQL statement.
     */
    public function get_qualifiers(): array {
        return $this->qualifiers;
    }
}