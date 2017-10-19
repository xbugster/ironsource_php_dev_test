<?php
/**
 * Created by PhpStorm.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Models;

class Offers extends AbstractModel
{
    const TABLE_NAME = 'offer';

    /**
     * @desc Maps offers to countries to packages using Stored Procedure. Returns number of records created.
     * @return int
     */
    public function mapOffers() : int {
        $statement = $this->_connection->prepare('call generate_package_offers()');
        $statement->execute();
        return (int)$statement->fetch()['counter'];
    }

    /**
     * @desc retrieve 50 offers, to show before filtering...
     * @todo implement pagination !
     * @return array
     */
    public function getRecentOffers() : array {
        /** @var \PDO $connection */
        $statement = $this->_connection->prepare(
            'select 
                       o.id,o.name,o.is_enabled,o.created_time,o.updated_time,o.installs_limit_per_day 
                       from ' . self::TABLE_NAME . ' as o LIMIT 50'
        );
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @desc retrieve offer by id
     * @param null $id
     * @return array
     */
    public function getOfferById($id = null) {
        if (is_null($id)) {
            return [];
        }
        $statement = $this->_connection->prepare('
            select o.id, o.name, o.is_enabled, o.installs_limit_per_day, o.content
            from ' . self::TABLE_NAME . ' as o where o.id = :id
        ');
        $statement->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @desc Method responsible for filtering offers by country id and package id.
     * @param null $packageId
     * @param null $countryId
     * @return array
     */
    public function getOffersByPackageAndCountry($packageId = null, $countryId = null) : array {
        if (is_null($packageId) || is_null($countryId)) {
            return [];
        }
        /** @var \PDO $connection */
        $statement = $this->_connection->prepare('
          select 
          o.id,o.name,o.is_enabled,o.created_time,o.updated_time,o.installs_limit_per_day,po.package_id 
          from ' . self::TABLE_NAME . ' as o left join package_offer as po ON (o.id = po.offer_id)
          WHERE po.package_id = :package_id and po.country_id = :country_id              
        ');
        $statement->bindValue(':package_id', $packageId, \PDO::PARAM_INT);
        $statement->bindValue(':country_id', $countryId, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @desc self explanatory
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
              (`name`, `is_enabled`, `installs_limit_per_day`, `content`, `created_time`)
              VALUES
              (:offer_name, :is_enabled, :installs_limit, :content, NOW())
        ');
        $statement->bindValue(':offer_name', $data['name'], \PDO::PARAM_STR);
        $statement->bindValue(':is_enabled', (bool)$data['is_enabled'], \PDO::PARAM_BOOL);
        $statement->bindValue(':installs_limit', $data['installs_limit_per_day'], \PDO::PARAM_INT);
        $statement->bindValue(':content', $data['content'], \PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * @param null $id
     * @return bool
     */
    public function remove($id = null) {
        if (is_null($id)) {
            return false;
        }

        $this->_connection->beginTransaction();

        $statement = $this->_connection->prepare('DELETE from package_offer WHERE offer_id = :offer_id');
        $statement->bindValue(':offer_id', (int)$id, \PDO::PARAM_INT);
        $statement->execute();
        $poResult = $statement->rowCount();

        $statement = $this->_connection->prepare('DELETE from ' . self::TABLE_NAME . ' WHERE id = :id');
        $statement->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        $statement->execute();
        $oResult = $statement->rowCount();

            $this->_connection->commit();

        return (bool)$poResult && (bool)$oResult;
    }

    /**
     * @param array $offer
     * @return bool
     */
    public function update(array $offer = array()) {
        # very very very simple validation
        if (!array_key_exists('id', $offer)
            || !array_key_exists('is_enabled', $offer)
            || !array_key_exists('name', $offer)
            || !array_key_exists('content', $offer)
            || !array_key_exists('installs_limit_per_day', $offer)
        ) {
            return false;
        }

        /** @var \PDOStatement $statement */
        $statement = $this->_connection->prepare('
            UPDATE ' . self::TABLE_NAME . ' SET 
            `name` = :offer_name, is_enabled = :is_enabled, installs_limit_per_day = :daily_limit, content = :content
            WHERE id = :id
        ');
        $statement->bindValue(':offer_name', $offer['name'], \PDO::PARAM_STR);
        $statement->bindValue(':is_enabled', (bool)$offer['is_enabled'], \PDO::PARAM_BOOL);
        $statement->bindValue(':daily_limit', (int)$offer['installs_limit_per_day'], \PDO::PARAM_INT);
        $statement->bindValue(':content', $offer['content'], \PDO::PARAM_STR);
        $statement->bindValue(':id', (int)$offer['id'], \PDO::PARAM_INT);
        $statement->execute();
        return (bool)$statement->rowCount();
    }
}