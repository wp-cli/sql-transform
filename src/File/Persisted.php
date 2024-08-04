<?php

namespace WP_CLI\SqlTransform\File;

use WP_CLI\SqlTransform\File;

final class Persisted implements File {

    private $file;
    private $handle;

    /**
     * Persisted file constructor.
     *
     * @param string $file The file path.
     */
    public function __construct( string $file ) {
        $this->file = $file;
        $this->handle = fopen( $file, 'r' );
    }

    /**
     * Check if there are more SQL statements in the file.
     *
     * @return bool True if there are more SQL statements, false otherwise.
     */
    public function has_next_statement(): bool {
        return ! feof( $this->handle );
    }

    /**
     * Get the next SQL statement from the file.
     *
     * @return string The next SQL statement.
     * @throws \RuntimeException If there are no more SQL statements.
     */
    public function get_next_statement(): string {
        $statement = '';
        while ( ( $line = fgets( $this->handle ) ) !== false ) {
            $statement .= $line;
            if ( substr( rtrim( $line ), -1 ) === ';' ) {
                break;
            }
        }
        return $statement;
    }

    /**
     * Persisted file destructor.
     *
     * @return void
     */
    public function __destruct() {
        fclose( $this->handle );
    }
}