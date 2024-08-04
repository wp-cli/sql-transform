<?php

namespace WP_CLI\SqlTransform;

use WP_CLI\SqlTransform\Sql;

interface Renderer {

    /**
     * Render a collection of SQL statements into a single string.
     *
     * @param Sql\Statement ...$statements The SQL statements to render.
     * @return string The rendered SQL statements.
     */
    public function render( Sql\Statement ...$statements ): string;
}