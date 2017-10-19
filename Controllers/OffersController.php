<?php
/**
 * Created by PhpStorm.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Controllers;

use Core\AbstractController;
use Core\Http\Response;
use Models\Offers;

class OffersController extends AbstractController
{
    /**
     * @desc action method which should return Response.
     *       Pagination not implemented on backend, nor frontend as we are working with very limited amount now.
     * @todo : add pagination...
     */
    public function showAction() : Response {
        $model = new Offers();
        $getParams = $this->getRequest()->getGetParameters();
        $packages = [];
        // scenario 1) get by package and country ids.
        if (isset($getParams['package']) && isset($getParams['country'])) {
            $packages = $model->getOffersByPackageAndCountry($getParams['package'], $getParams['country']);
        }
        // scenario 2) get offer by id.
        if (isset($getParams['id'])) {
            $packages = $model->getOfferById($getParams['id']);
        }
        // scenario 3) get all offers (actually "some" 50 offers) as if we take all offers, will crash any browser.
        if (!isset($getParams['package']) && !isset($getParams['country']) && !isset($getParams['id'])){
            $packages = $model->getRecentOffers();
        }

        return $this->makeResponse()->setJsonResponseType()->setSuccess()->setContent($packages);
    }

    /**
     * @desc Mapping offer to countries to packages !
     *
     * @return Response
     */
    public function mapOffersAction() : Response {
        $model = new Offers();
        return $this->makeResponse()->setJsonResponseType()->setSuccess()->setContent($model->mapOffers());
    }

    /**
     * @desc Serving POST method.
     *
     * @return Response
     */
    public function createAction() : Response {
        $data = $this->getRequest()->getPostParameters();
        $model = new Offers();
        $response = $this->makeResponse()->setJsonResponseType()->setContent([]);
        if (!$model->create($data)) {
            return $response->setFailure();
        }

        return $response->setSuccess();
    }

    /**
     * @desc Serving DELETE method
     *
     * @return Response
     */
    public function removeAction() : Response {
        $id = $this->getRequest()->getGetParameters()['id'] ?? null;
        $response = $this->makeResponse()->setJsonResponseType();
        $model = new Offers();
        if (!$model->remove($id)) {
            return $response->setContent('Could not remove supplied offer')->setFailure();
        }

        return $response->setSuccess()->setContent([]);
    }

    /**
     * @desc REST POST method
     * @return Response
     */
    public function updateAction() : Response {
        $data = $this->getRequest()->getPostParameters();
        $model = new Offers();
        $response = $this->makeResponse()->setJsonResponseType()->setContent([]);
        if (!$model->update($data)) {
            return $response->setFailure();
        }

        return $response->setSuccess();
    }
}