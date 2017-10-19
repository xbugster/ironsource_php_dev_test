<?php
/**
 * Created by PhpStorm.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Models;

class Countries extends AbstractModel
{
    const TABLE_NAME = 'country';

    /**
     * @desc end point for drop down data.
     * @return array
     */
    public function getKeyValueForDropdown() {
        /** @var \PDOStatement $statement */
        $statement = $this->_connection->prepare( '
          select id, CONCAT(\'(\',country_code,\') \',`name`) as `name` from ' . self::TABLE_NAME . ' order by country_code
        ');
        $statement->execute();

        if ( !$resultSet = $statement->fetchAll( \PDO::FETCH_ASSOC ) ) {
            return [];
        }

        return $resultSet;
    }
}