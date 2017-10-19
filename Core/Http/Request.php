<?php
/**
 * @desc    Request Object - on init aggregate & encapsulates necessary information from request.
 * @author  Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Core\Http;


class Request
{
    const REQUEST_TYPE_JSON = 'json';
    const REQUEST_TYPE_REGULAR = 'regular';

    private $_requestMethod = null;

    private $_getParameters = array();

    private $_postParameters = array();

    private $_requestHost = null;

    private $_requestPath = null;

    private $_requestReferer = null;

    private $_userIp = null;

    private $_requestHeaders = array();

    private $_requestType = null;

    /**
     * Constructs Request object with aggregation of required information from request.
     */
    public function __construct() {
        $this->_requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        $this->_requestHost = $_SERVER['HTTP_HOST'];
        $this->_userIp = $_SERVER['REMOTE_ADDR'];
        $this->_requestPath = $this->_parsePathInfo();
        $this->_requestHeaders = $this->_getHeaders();
        $this->_requestType = $this->_identifyRequestType();
        $this->_requestReferer = $this->_parseReferer();
        $this->_getParameters = $this->_aggregateGet();
        $this->_postParameters = $this->_aggregatePost();
    }

    /**
     * @desc Returns all headers sent with request (headers from server itself)
     * @return array
     */
    private function _getHeaders() : array {
        return array_change_key_case(getallheaders(), CASE_LOWER);
    }

    /**
     * @desc refer getter from env vars. done separately to not confuse constructor as this var might not exist.
     * @return string
     */
    private function _parseReferer() : string {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }

    /**
     * @desc PATH_INFO getter from env vars. done separately to not confuse constructor as this var might not exist.
     * @return string
     */
    private function _parsePathInfo() : string {
        return $_SERVER['PATH_INFO'] ?? '/home';
    }

    /**
     * @desc self explanatory
     * @return array
     */
    private function _aggregateGet() : array {
        return $_GET;
    }

    /**
     * @desc Aggregate post parameters, this includes aggregating put and patch parameters as well.
     *       Method taking into account previously identified request content type. in case of header
     *       Content-type: application/json that request arrived with, it is wise, that post/put/patch body is
     *       JSON encoded and should be parsed accordingly. Read the code.
     *
     * @return array
     */
    private function _aggregatePost() : array {
        $parsed_parameters = array();
        if ($this->_requestType == ContentTypes::TYPE_JSON) {
            $parsed_parameters = json_decode(file_get_contents('php://input'), true);
        } else {
            parse_str(file_get_contents('php://input'), $parsed_parameters);
        }
        return $parsed_parameters;
    }

    /**
     * @desc identifies request type accordingly to predefined contact types.
     * @return string
     */
    public function _identifyRequestType() : string {
        if (isset($this->_requestHeaders['content-type'])
            && strpos($this->_requestHeaders['content-type'], ContentTypes::TYPE_JSON) !== false
        ) {
            return ContentTypes::TYPE_JSON;
        }
        return ContentTypes::TYPE_STANDARD;
    }

    /**
     * @desc parsed headers getter
     * @return array
     */
    public function getRequestHeaders() : array {
        return $this->_requestHeaders;
    }

    /**
     * @desc returns visitor's ip address
     * @return null|string
     */
    public function getUserIp() :? string {
        return $this->_userIp;
    }

    /**
     * @desc return http referer
     * @return null|string
     */
    public function getReferer() :? string {
        return $this->_requestReferer;
    }

    /**
     * @desc returns requested path. mostly used for routing purposes.
     * @return null|string
     */
    public function getPath() :? string {
        return $this->_requestPath;
    }

    /**
     * @desc returns requested host e.g. php.net
     * @return null|string
     */
    public function getHost() :? string {
        return $this->_requestHost;
    }

    /**
     * @desc returns request method
     * @return null|string
     */
    public function getRequestMethod() :? string {
        return $this->_requestMethod;
    }

    /**
     * @desc return GET parameters
     * @return array
     */
    public function getGetParameters() : array {
        return $this->_getParameters;
    }

    /**
     * @desc return POST parameters
     * @return array
     */
    public function getPostParameters() : array {
        return $this->_postParameters;
    }

    /**
     * @desc returns PUT parameters. falls back to using postParameters().
     * @return array
     */
    public function getPutParameters() : array {
        return $this->_postParameters;
    }

    /**
     * @desc returns PATCH parameters. falls back to using postParameters().
     * @return array
     */
    public function getPatchParameters() : array {
        return $this->_postParameters;
    }

    /**
     * @desc a special treat to DELETE request method, which could not contain parameters.
     * @return array
     */
    public function getDeleteParameters() : array {
        return array();
    }
}