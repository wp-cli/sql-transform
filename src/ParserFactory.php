<?php

namespace WP_CLI\SqlTransform;

use InvalidArgumentException;
use RuntimeException;

/**
 * Factory for creating SQL parsers.
 *
 * @package WP_CLI\SqlTransform
 */
final class ParserFactory {

    /**
     * The mapping of SQL dialects to parser classes.
     *
     * @var array<string, string>
     */
    const MAPPINGS = [
        Sql\Dialect::MYSQL   => Parser\MySQL::class,
        Sql\Dialect::MARIADB => Parser\MariaDB::class,
        Sql\Dialect::SQLITE  => Parser\SQLite::class,
    ];
    
    /**
    * Create a new SQL parser for the given dialect.
    *
    * @param string $dialect The SQL dialect to parse.
    * @return Parser The SQL parser.
    */
    public static function create( string $dialect ): Parser {
        if ( ! isset( self::MAPPINGS[ $dialect ] ) ) {
            throw new InvalidArgumentException( 'Unsupported SQL dialect: ' . $dialect );
        }
        $parser_class = self::MAPPINGS[ $dialect ];
        if ( ! class_exists( $parser_class ) ) {
            throw new RuntimeException( 'Parser not implemented yet for dialect: ' . $dialect );
        }
        return new $parser_class();
    }
}