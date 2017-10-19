<?php
/**
 * Created by PhpStorm.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Controllers;

use Core\AbstractController;
use Core\Http\Response;

class HomeController extends AbstractController
{
    /**
     * @desc action method which should return Response
     */
    public function indexAction() : Response {
        return $this->makeResponse()
                    ->setSuccess()
                    ->setJsonResponseType()
                    ->setContent('Please follow API documentation, which does not exist :P');
    }
}