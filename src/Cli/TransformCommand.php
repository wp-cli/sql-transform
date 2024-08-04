<?php

namespace WP_CLI\SqlTransform\Cli;

use RuntimeException;
use WP_CLI\SqlTransform\File;
use WP_CLI\SqlTransform\Transformer;
use WP_CLI\SqlTransform\ParserFactory;
use WP_CLI\SqlTransform\RendererFactory;

final class TransformCommand {

    private $filepath;
    private $from_dialect;
    private $to_dialect;

    public function __construct( array $arguments ) {
        list( $this->filepath, $this->from_dialect, $this->to_dialect ) = $this->parse_arguments( $arguments );
    }

    public function process() {
        $transformer = new Transformer( new ParserFactory(), new RendererFactory() );
        if ( '-' === $this->filepath ) {
            $this->filepath = 'php://stdin';
        }

        $sql_file = new File\Persisted( $this->filepath );

        foreach ( $transformer->transform( $sql_file, $this->from_dialect, $this->to_dialect ) as $transformed_statement ) {
            echo $transformed_statement;
        }
    }

    /**
     * Parse the command line arguments.
     *
     * Required arguments:
     * <filepath> - The path to the SQL file to transform. If the path is a dash (i.e. '-'), STDIN is used.
     *
     * Optional arguments:
     * [--from-dialect=<from-dialect>] - The dialect of the input SQL statements. Default is 'mysql'.
     * [--to-dialect=<to-dialect>]     - The dialect to transform the input SQL statements to. Default is 'sqlite'.
     */
    private function parse_arguments( array $arguments ) {
        if ( empty( $arguments ) ) {
            throw new RuntimeException( 'No arguments provided.' );
        }
        $executable = array_shift( $arguments );
        $filepath   = array_shift( $arguments );

        $from_dialect = 'mysql';
        $to_dialect   = 'sqlite';

        foreach ( $arguments as $argument ) {
            if ( preg_match( '/^--from-dialect=(.*)$/', $argument, $matches ) ) {
                $from_dialect = $matches[1];
            } elseif ( preg_match( '/^--to-dialect=(.*)$/', $argument, $matches ) ) {
                $to_dialect = $matches[1];
            }
        }

        return [ $filepath, $from_dialect, $to_dialect ];
    }
}