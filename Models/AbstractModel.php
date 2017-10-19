<?php
/**
 * Created by PhpStorm.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Models;

use Core\Database\DbAdapter;

abstract class AbstractModel
{
    /**
     * @var null|\PDO
     */
    protected $_connection = null;

    /**
     * AbstractModel constructor.
     */
    public function __construct() {
        $this->_connection = DbAdapter::getConnection();
    }

    /**
     * @desc Key=>Value mapper for drop downs.
     *       Results set must consist of key and value records, which you columns you assign in your query is
     *       a choice for developer.
     *       sample results set:
     *          [['key'=>8, 'value'=>'name1'],['key'=>2,'value'=>'name2']]
     *       expected result after mapping executed:
     *          [[8=>'name1', 2=>'name2']]
     *
     *
     * @deprecated Currently deprecated, as angular only need sets with 2 columns.
     * @param array $resultSet sample: [['key'=>8, 'value'=>'name1'],['key'=>2,'value'=>'name2']]
     * @return array sample: [[8=>'name1', 2=>'name2']]
     */
    public function remapForDropdowns(array $resultSet = array()) {
        $keyValueMap = [];
        foreach($resultSet as $record) {
            $keyValueMap[] = [$record['key'] => $record['value']];
        }
        return $keyValueMap;
    }
}