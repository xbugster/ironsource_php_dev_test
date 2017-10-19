<?php

namespace Core;

class Registry
{
    private $_registry = array();

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get( $key = null ) {
        return $this->_registry[$key] ?? null;
    }

    /**
     * @param string $key
     * @param mixed $object
     * @return bool
     */
    public function set( $key = null, $object = null ) :? bool {
        if ( $key == null ) {
            return false;
        }
        $this->_registry[$key] = $object;
        return true;
    }
}