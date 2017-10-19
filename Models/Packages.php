<?php
/**
 * Package Model
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Models;

class Packages extends AbstractModel
{
    const TABLE_NAME = 'package';

    /**
     * @param null $id
     * @return array
     */
    public function getPackages($id = null) {
        if (!is_null($id)) {
            return $this->getById($id);
        }
        return $this->getAll();
    }

    /**
     * @param null $id
     * @return array
     */
    public function getById($id = null) {
        /* @var \PDOStatement */
        $statement = $this->_connection->prepare( '
                select * from ' . self::TABLE_NAME  . ' WHERE id = :id
            ');
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        if ( !$resultSet = $statement->fetchAll( \PDO::FETCH_ASSOC ) ) {
            return [];
        }

        return $resultSet;
    }

    /**
     * @return array
     */
    public function getAll() {
        /** @var \PDOStatement $statement */
        $statement = $this->_connection->prepare( 'select * from ' . self::TABLE_NAME );
        $statement->execute();

        if ( !$resultSet = $statement->fetchAll( \PDO::FETCH_ASSOC ) ) {
            return [];
        }
        return $resultSet;
    }

    /**
     * @desc Serving PUT method.
     * @param array $package
     * @return bool
     */
    public function update(array $package = array()) : bool {
        if (!array_key_exists('id', $package)
            || !array_key_exists('is_enabled', $package)
            || !array_key_exists('name', $package)
        ) {
            return false;
        }

        /** @var \PDOStatement $statement */
        $statement = $this->_connection->prepare('
            UPDATE package SET `name` = :package_name, is_enabled = :is_enabled WHERE id = :id
        ');
        $statement->bindValue(':package_name', $package['name'], \PDO::PARAM_STR);
        $statement->bindValue(':is_enabled', (bool)$package['is_enabled'], \PDO::PARAM_BOOL);
        $statement->bindValue(':id', (int)$package['id'], \PDO::PARAM_INT);
        $statement->execute();
        return (bool)$statement->rowCount();
    }

    /**
     * @desc method for dropdown. will return set consists of following structure
     *      [
     *          ['id' => 123123, 'name' => 'xyz],
     *          ['id' => 121, 'name' => 'zxy]
     *      ]
     * @return array
     */
    public function getPackagesAsKeyValue() {
        /** @var \PDOStatement $statement */
        $statement = $this->_connection->prepare(
            'select id, `name` from ' . self::TABLE_NAME . ' order by `name`
        ');
        $statement->execute();

        if ( !$resultSet = $statement->fetchAll( \PDO::FETCH_ASSOC ) ) {
            return [];
        }

        return $resultSet;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data = array()) : bool {
        if (empty($data)) {
            return false;
        }
        // this happens on angular side, when not checked in->out, checkbox might be blank, rather than false...
        if (empty($data['is_enabled'])) {
            $data['is_enabled'] = false;
        }

        $statement = $this->_connection->prepare('
              INSERT INTO ' . self::TABLE_NAME . ' 
              (`name`, `is_enabled`, `created_time`)
              VALUES
              (:offer_name, :is_enabled, NOW())
        ');
        $statement->bindValue(':offer_name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue(':is_enabled', (bool)$data['is_enabled'], \PDO::PARAM_BOOL);

        return $statement->execute();
    }

    /**
     * @desc packager.
     * @return bool
     */
    public function generatePackagesAsFiles() {
        $step = 500;
        $offset = 0;
        $writer = new PackageFileWriter();
        $writesCounter = 0;
        while ($combinations = $this->getPackageCountryCombinations($offset, $step)) {
            foreach($combinations as $countryPackageCombo ) {
                if ( !$packageOffers = $this->getPackagesByCountriesMappedToOffers(
                    $countryPackageCombo['id'], $countryPackageCombo['country_id']
                ) ) {
                    $packageOffers = []; # if there is no offers for combo, make it blank array.
                }
                $package = $this->preparePackageDataForWriting($countryPackageCombo, $packageOffers);
                $writer->writePackage($package);
                ++$writesCounter;
            }
            $offset += $step;
        }
        return $writesCounter;
    }

    /**
     * @param array $package
     * @param array $offers
     * @return array
     */
    public function preparePackageDataForWriting(array $package = array(), array $offers = array()) : array {
        unset($package['country_id']);
        $package['offers'] = $offers;
        return $package;
    }

    /**
     * @desc Returns all available combinations. enforces to utilize offset and amount.
     *       Without these parameters - system may consume all available memory! you warned now.
     * @param int $offset
     * @param int $amount
     * @return array
     */
    public function getPackageCountryCombinations($offset = 0, $amount = 0) {
        if ( empty( $amount ) ) {
            return [];
        }

        $statement = $this->_connection->prepare('
            select  p.name, p.is_enabled, po.package_id as id, c.country_code as country, NOW() as file_created_at, po.country_id
            from package_offer as po
            left join package as p on (po.package_id = p.id)
            left join country as c on (po.country_id = c.id)
            group by po.package_id, po.country_id
            limit :offset,:amount_to_take;
        ');
        $statement->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $statement->bindValue(':amount_to_take', $amount, \PDO::PARAM_INT);
        $statement->execute();

        if ( !$dataset = $statement->fetchAll( \PDO::FETCH_ASSOC ) ) {
            return [];
        }
        return $dataset;
    }


    /**
     * @desc Returns Package's Offers for supplied Package Id and Country Id
     *
     * @param null $packageId
     * @param null $countryId
     * @return array
     */
    public function getPackagesByCountriesMappedToOffers($packageId = null, $countryId = null) {
        if ( empty($packageId) || empty($countryId) ) {
            return [];
        }

        $statement = $this->_connection->prepare('
            SELECT 
                o.name, o.is_enabled, o.content, o.created_time, o.installs_limit_per_day, po.offer_id as id
            FROM package_offer as po
            LEFT JOIN offer as o 
              ON (po.offer_id = o.id)
            WHERE po.package_id = :package_id AND po.country_id = :country_id
        ');
        $statement->bindValue(':package_id', $packageId, \PDO::PARAM_INT);
        $statement->bindValue(':country_id', $countryId, \PDO::PARAM_INT);
        $statement->execute();

        if ( !$resultSet = $statement->fetchAll( \PDO::FETCH_ASSOC ) ) {
            return [];
        }
        return $resultSet;
    }

    /**
     * @desc name is self descriptive.
     *       wrapped into transaction, as NO ACTION is set on FK level and we likely won't remove only
     *       one part of the data, otherwise we could solve it using delete+join.
     *
     * @param null $id
     * @return bool
     */
    public function remove($id = null) {
        if (is_null($id)) {
            return false;
        }

        $this->_connection->beginTransaction();

        $statement = $this->_connection->prepare('DELETE from package_offer WHERE package_id = :pack_id');
        $statement->bindValue(':pack_id', (int)$id, \PDO::PARAM_INT);
        $statement->execute();
        $poResult = $statement->rowCount();

        $statement = $this->_connection->prepare('DELETE from ' . self::TABLE_NAME . ' WHERE id = :id');
        $statement->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $statement->execute();
        $pResult = $statement->rowCount();

        $this->_connection->commit();

        return (bool)$poResult && (bool)$pResult;
    }
}