<?php

namespace WP_CLI\SqlTransform;

use InvalidArgumentException;
use RuntimeException;

/**
 * Factory for creating SQL renderers.
 *
 * @package WP_CLI\SqlTransform
 */
final class RendererFactory {

    /**
     * The mapping of SQL dialects to renderer classes.
     *
     * @var array<string, string>
     */
    const MAPPINGS = [
        Sql\Dialect::MYSQL   => Renderer\MySQL::class,
        Sql\Dialect::MARIADB => Renderer\MariaDB::class,
        Sql\Dialect::SQLITE  => Renderer\SQLite::class,
    ];
    
    /**
    * Create a new SQL renderer for the given dialect.
    *
    * @param string $dialect The SQL dialect to parse.
    * @return Renderer The SQL renderer.
    */
    public static function create( string $dialect ): Renderer {
        if ( ! isset( self::MAPPINGS[ $dialect ] ) ) {
            throw new InvalidArgumentException( 'Unsupported SQL dialect: ' . $dialect );
        }
        $renderer_class = self::MAPPINGS[ $dialect ];
        if ( ! class_exists( $renderer_class ) ) {
            throw new RuntimeException( 'Renderer not implemented yet for dialect: ' . $dialect );
        }
        return new $renderer_class();
    }
}