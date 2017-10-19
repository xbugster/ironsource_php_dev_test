<?php
/**
 * Abstract controller with basic functionality and some of crucial methods that could not be overwritten.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Core;

use Core\Http\Request;
use Core\Http\Response;

abstract class AbstractController
{
    /**
     * @var Request|null
     */
    private $_request = null;

    /**
     * AbstractController constructor.
     * @param Request $request
     */
    final public function __construct(Request $request)
    {
        $this->_request = $request;
    }

    /**
     * @return Request
     */
    final public function getRequest() : Request {
        return $this->_request;
    }

    /**
     * @return Response
     */
    final public function makeResponse() : Response {
        return new Response();
    }
}