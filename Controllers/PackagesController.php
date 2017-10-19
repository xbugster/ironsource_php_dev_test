<?php
/**
 * Created by PhpStorm.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Controllers;

use Core\AbstractController;
use Core\Http\Response;
use Models\Packages;

class PackagesController extends AbstractController
{
    /**
     * @desc REST GET Method.
     */
    public function showAction() : Response {
        $id = $this->getRequest()->getGetParameters()['id'] ?? null;
        $model = new Packages();
        $packages = $model->getPackages($id);
        $response = $this->makeResponse()->setJsonResponseType()->setSuccess();
        if ( empty( $packages ) ) {
            $response->setContent([]);
        } else {
            $response->setContent($packages);
        }

        return $response;
    }

    /**
     * @desc Action to generate package offers files.
     * @return Response
     */
    public function generatePackagesFilesAction() : Response {
        $model = new Packages();
        $result = $model->generatePackagesAsFiles();
        $response = $this->makeResponse()->setJsonResponseType();
        if ( $result === false ) {
            $response->setFailure()
                     ->setContent('something wrong');
        } else {
            $response->setSuccess()
                     ->setContent($result);
        }

        return $response;
    }

    /**
     * @desc Action to get packages as key=>value (id,name) for drop downs.
     * @return Response
     */
    public function getKeyValuePackagesAction() : Response {
        $model = new Packages();
        $packages = $model->getPackagesAsKeyValue();
        $response = $this->makeResponse()->setJsonResponseType()->setSuccess();
        if ( empty( $packages ) ) {
            $response->setContent([]);
        } else {
            $response->setContent($packages);
        }

        return $response;
    }

    /**
     * @desc REST DELETE Method.
     * @return Response
     */
    public function removeAction() : Response {
        $id = $this->getRequest()->getGetParameters()['id'] ?? null;
        $response = $this->makeResponse()->setJsonResponseType();
        $model = new Packages();
        if (!$model->remove($id)) {
            return $response->setContent('Could not remove supplied package')->setFailure();
        }

        return $response->setSuccess()->setContent([]);
    }

    /**
     * @desc REST POST method
     * @return Response
     */
    public function createAction() : Response {
        $data = $this->getRequest()->getPostParameters();
        $model = new Packages();
        $response = $this->makeResponse()->setJsonResponseType()->setContent([]);
        if (!$model->create($data)) {
            return $response->setFailure();
        }

        return $response->setSuccess();
    }

    /**
     * @desc REST POST method
     * @return Response
     */
    public function updateAction() : Response {
        $data = $this->getRequest()->getPostParameters();
        $model = new Packages();
        $response = $this->makeResponse()->setJsonResponseType()->setContent([]);
        if (!$model->update($data)) {
            return $response->setFailure();
        }

        return $response->setSuccess();
    }
}