<?php

namespace WP_CLI\SqlTransform\Sql;

interface Dialect {
    const MYSQL = 'mysql';
    const MARIADB = 'mariadb';
    const SQLITE = 'sqlite';
}