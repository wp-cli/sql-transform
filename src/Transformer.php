<?php

namespace WP_CLI\SqlTransform;

final class Transformer {

    private $parser_factory;
    private $renderer_factory;

    public function __construct( ParserFactory $parser_factory, RendererFactory $renderer_factory ) {
        $this->parser_factory   = $parser_factory;
        $this->renderer_factory = $renderer_factory;
    }

    /**
    * Transform a string of SQL statements from one dialect to another.
    *
    * @param File   $sql_file     The SQL statements to transform.
    * @param string $from_dialect The dialect of the input SQL statements.
    * @param string $to_dialect   The dialect to transform the input SQL statements to.
    */
    public function transform( File $sql_file, string $from_dialect, string $to_dialect ) {
        $parser   = $this->parser_factory->create( $from_dialect );
        $renderer = $this->renderer_factory->create( $to_dialect );
        while ( $sql_file->has_next_statement() ) {
            $statement = $parser->parse_statement( $sql_file->get_next_statement() );
            yield $renderer->render( $statement );
        }
    }
}