<?php
/**
 * Response object which is used to for rendering response at the end of request's lifecycle
 * @author Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Core\Http;


class Response
{

    /**
     * @var array
     */
    protected $_headers = [];

    /**
     * @var string
     */
    protected $_content = '';

    /**
     * @var bool
     */
    protected $_success = true;

    /**
     * @desc property to store current content type, to not lookup in headers.
     * @var null
     */
    protected $_contentType = null;
    /**
     * Response constructor.
     * by default sets JSON as fallback, if you are not using setJsonResponseType() of builder or PlainResponseType().
     */
    public function __construct() {
        $this->setContentType(ContentTypes::TYPE_JSON_WITH_CHARSET_UTF8);
        $this->addHeader('Access-Control-Allow-Origin', 'http://localhost:4200');
    }

    /**
     * @desc used to set success to true, in case of json, will indicate {success: true, data: $this->_content}
     * @return Response
     */
    public function setSuccess() : Response {
        $this->_success = true;
        return $this;
    }

    /**
     * @desc used to set success to false, in case of json, will indicate {success: false, data: $this->_content}
     * @return Response
     */
    public function setFailure() : Response {
        $this->_success = false;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSuccess() : bool {
        return $this->_success;
    }

    /**
     * @desc Getter for current response content type.
     * @return string
     */
    public function getContentType() : string {
        return $this->_contentType;
    }

    /**
     * @desc header setter
     * @param string|null $name
     * @param string|null $value
     * @return Response
     */
    public function addHeader( $name = null, $value = null ) : Response {
        if ( empty( $name ) || empty( $value ) ) {
            return $this;
        }
        $this->_headers[ $name ] = $value;
        return $this;
    }

    /**
     * @desc header remover
     * @param string|null $name
     * @return Response
     */
    public function removeHeader( $name = null ) : Response {
        if ( empty( $name ) ) {
            return $this;
        }
        unset( $this->_headers[ $name ] );
        return $this;
    }

    /**
     * @desc Header setter as array, utilizes underlying addHeader()
     * @param array $headersArray
     * @return Response
     */
    public function addHeaders( array $headersArray = array() ) : Response {
        foreach ( $headersArray as $headerName => $headerValue ) {
            $this->addHeader( $headerName, $headerValue );
        }
        return $this;
    }

    /**
     * @desc remove headers array
     * @param array $headerNamesArray
     * @return Response
     */
    public function removeHeaders( array $headerNamesArray = array() ) : Response {
        foreach ( $headerNamesArray as $headerName ) {
            $this->removeHeader( $headerName );
        }
        return $this;
    }

    /**
     * @desc Accumulated headers getter
     * @return array
     */
    public function getHeaders() : array {
        return $this->_headers;
    }

    /**
     * @desc Method to simplify usage if needed, we advice to use ContentTypes::TYPE_* constants.
     * @param string|null $type currently allowed types application/json|text/plain|text/html
     * @return Response
     */
    private function setContentType($type = null) : Response {
        if ( empty( $type ) ) {
            return $this;
        }
        $type = strtolower($type);
        ## There will not be a change in type, if improper type is supplied.
        if ( $type == ContentTypes::TYPE_JSON_WITH_CHARSET_UTF8
            || $type == ContentTypes::TYPE_HTML_WITH_CHARSET_UTF8
            || $type == ContentTypes::TYPE_PLAIN_TEXT_WITH_CHARSET_UTF8
        ) {
            $this->addHeader('content-type', $type );
            $this->_contentType = $type;
        }
        return $this;
    }

    /**
     * @desc typical facade.
     * @return Response
     */
    public function setJsonResponseType() : Response {
        $this->setContentType(ContentTypes::TYPE_JSON_WITH_CHARSET_UTF8);
        return $this;
    }

    /**
     * @desc typical facade.
     * @return Response
     */
    public function setHtmlResponseType() : Response {
        $this->setContentType(ContentTypes::TYPE_HTML_WITH_CHARSET_UTF8);
        return $this;
    }

    /**
     * @desc typical facade.
     * @return Response
     */
    public function setPlainTextResponseType() : Response {
        $this->setContentType(ContentTypes::TYPE_PLAIN_TEXT_WITH_CHARSET_UTF8);
        return $this;
    }

    /**
     * @desc Content setter.
     * @param string $content
     * @return Response
     */
    public function setContent($content = '') : Response {
        $this->_content = $content;
        return $this;
    }

    /**
     * @desc content getter. might apply some logic for different content types.
     * @return string
     */
    public function getContent() {
        return $this->_content;
    }
}