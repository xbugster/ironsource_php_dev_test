<?php
/**
 * Response handler is the last event in application life cycle.
 * Responsible for rendering response based on parameters stored in Response object which is
 * Returned from controller's action execution and might be built using ResponseBuilder.
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Core\Events;


use Core\Http\ContentTypes;
use Core\Http\Response;

class ResponseHandler implements \SplObserver
{
    /**
     * @param \SplSubject|\Core\App $subject
     */
    public function update(\SplSubject $subject) : void
    {
        if ($subject->isNotToSendContent()) {
            $this->respondToOptionsWithAllowRequested();

            return;
        }
        $response = $subject->getResponse();
        $this->sendHeaders( $response );
        $this->renderResponse( $response );
    }

    /**
     * @desc simple dumb CORS protection bypass.
     */
    protected function respondToOptionsWithAllowRequested() {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
        }
    }

    /**
     * @desc render response according to its content type - high level method
     * @param Response $response
     */
    protected function renderResponse( Response $response ) : void {
        switch($response->getContentType()) {
            case ContentTypes::TYPE_JSON_WITH_CHARSET_UTF8:
                $this->renderJsonResponse( $response );
                break;
            case ContentTypes::TYPE_PLAIN_TEXT_WITH_CHARSET_UTF8:
                $this->renderPlainTextResponse( $response );
                break;
            case ContentTypes::TYPE_HTML_WITH_CHARSET_UTF8:
                $this->renderHtmlResponse( $response );
                break;
        }
    }

    /**
     * @desc Render json response. Did not want to go the route of adapters... - lower level method
     * @param Response $response
     */
    protected function renderJsonResponse( Response $response ) : void {
        $content = array(
            'success' => $response->getSuccess(),
            'data' => $response->getContent()
        );
        print json_encode($content);
    }

    /**
     * @desc Render response as html- lower level method
     * @param Response $response
     */
    protected function renderHtmlResponse( Response $response ) : void {
        print $response->getContent();
    }

    /**
     * @desc render response as plain text - lower level method
     * @param Response $response
     */
    protected function renderPlainTextResponse( Response $response ) : void {
        print $response->getContent();
    }

    /**
     * @desc Sending accumulated headers to browser.
     * @param Response $response
     */
    protected function sendHeaders( Response $response ) : void {
        foreach( $response->getHeaders() as $name => $value ) {
            header($name . ': ' . $value);
        }
    }
}