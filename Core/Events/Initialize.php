<?php
/**
 * @desc    Initialize event.
 * @author  Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Core\Events;

use Core\Database\DbAdapter;
use Core\Registry;

class Initialize implements \SplObserver
{
    /**
     * @desc part of spl observer interface. responsible for performing necessary event's job.
     * @param \SplSubject|\Core\App $subject
     */
    public function update(\SplSubject $subject) : void
    {
        $rootPath = $subject->getRootPath();
        $registry = new Registry();
        if (is_null($rootPath)) {
            $subject->halt("Application start up failure.");
            return;
        }
        $config = $this->getConfig($rootPath);
        $registry->set('config', $config);
        $registry->set('routes', $this->getRoutes($rootPath));
        $subject->setRegistry($registry);

        DbAdapter::$_password = $config['database']['password'];
        DbAdapter::$_database = $config['database']['database'];
        DbAdapter::$_username = $config['database']['username'];
        DbAdapter::$_hostname = $config['database']['hostname'];
    }

    /**
     * @param null $rootPath
     * @return array
     */
    private function getConfig($rootPath = null) : array {
        return require_once($rootPath . '/Config/Configuration.php');
    }

    /**
     * @param null $rootPath
     * @return array
     */
    private function getRoutes($rootPath = null) : array {
        return require_once($rootPath . '/Config/Routes.php');
    }
}