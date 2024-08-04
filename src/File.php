<?php

namespace WP_CLI\SqlTransform;

use RuntimeException;

interface File {

    /**
     * Check if there are more SQL statements in the file.
     *
     * @return bool True if there are more SQL statements, false otherwise.
     */
    public function has_next_statement(): bool;

    /**
     * Get the next SQL statement from the file.
     *
     * @return string The next SQL statement.
     * @throws RuntimeException If there are no more SQL statements.
     */
    public function get_next_statement(): string;
}