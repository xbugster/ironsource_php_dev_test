<?php
/**
 * Class Responsible for writing package files.
 */

namespace Models;


class PackageFileWriter
{
    const STORAGE_DIR = 'packages_files';
    /**
     * @desc Writing packages to files
     * @todo : Depend on Package Entity when setting up ORM.
     * @param array $package
     */
    public function writePackage(array $package = array()) : void {
        $fileName = $this->generateFileName($package);
        $dirName = $this->generateDirectoryName($package);
        $this->ensureDirectoryExistCreateIfNot($dirName);
        file_put_contents(
            $dirName . DIRECTORY_SEPARATOR . $fileName,
            json_encode($package, JSON_PRETTY_PRINT)
        );
    }

    /**
     * @desc Verifies if directory exists, if not - creates one.
     * @param $dir
     */
    public function ensureDirectoryExistCreateIfNot($dir) : void {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    /**
     * @desc generate file name based on package information
     * @Todo : have to be dependent on Package Entity
     * @param array $package
     * @return string
     */
    public function generateFileName(array $package = array()) : string {
        return $package['id'] . '_' . $package['country'];
    }

    /**
     * @desc generating directory name to store package file in.
     * @Todo : have to be dependent on Package Entity
     * @param array $package
     * @return string
     */
    public function generateDirectoryName(array $package = array()) : string {
        return implode(DIRECTORY_SEPARATOR, array(ROOT_DIR, self::STORAGE_DIR, $package['country']));
    }

}