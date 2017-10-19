<?php
/**
 * Database adapter.
 * Yes, no abstract layer, yes right, mysql adapter.
 * why? cause required only mysql support.
 */

namespace Core\Database;

class DbAdapter
{
    static private $_connection = null;

    static public $_username = null;
    static public $_password = null;
    static public $_database = null;
    static public $_hostname = null;

    /**
     * Private methods to prevent doubling instances.
     */
    private function __construct() {}
    private function __clone() {}

    /**
     * Get Connection
     * @return object PdoConnection
     */
    public static function getConnection()
    {
        if ( is_null( self::$_connection ) ) {
            self::$_connection = new \PDO(
                'mysql:dbname=' . self::$_database . ';host=' . self::$_hostname,
                self::$_username,
                self::$_password
            );
        }
        return self::$_connection;
    }
}