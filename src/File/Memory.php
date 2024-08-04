<?php

namespace WP_CLI\SqlTransform\File;

use WP_CLI\SqlTransform\File;

final class Memory implements File {

    /**
     * @var string[] The SQL statements.
     */
    private $statements;

    /**
     * Memory file constructor.
     *
     * @param string $sql The SQL statements.
     */
    public function __construct( string $sql ) {
        $this->statements = array_filter(array_map('trim', explode( ';', $sql )));
    }

    /**
     * Check if there are more SQL statements in the file.
     *
     * @return bool True if there are more SQL statements, false otherwise.
     */
    public function has_next_statement(): bool {
        return ! empty( $this->statements );
    }

    /**
     * Get the next SQL statement from the file.
     *
     * @return string The next SQL statement.
     * @throws \RuntimeException If there are no more SQL statements.
     */
    public function get_next_statement(): string {
        return empty( $this->statements ) ? '' : array_shift( $this->statements ) . ';';
    }
}