<?php
/**
 * Created by PhpStorm.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Controllers;

use Core\AbstractController;
use Core\Http\Response;
use Models\Countries;

class CountriesController extends AbstractController
{
    /**
     * @desc Return drop down valid data for countries.
     * @return Response
     */
    public function keyValueForDropdownAction() : Response {
        $model = new Countries();
        return $this->makeResponse()->setJsonResponseType()->setSuccess()
                    ->setContent($model->getKeyValueForDropdown());
    }
}