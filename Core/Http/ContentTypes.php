<?php
/**
 * Container dedicated to hold operational values related to request and response content types.
 * It is done to not hard code same types everywhere, or even in every instance which deals with content types.
 */

namespace Core\Http;


class ContentTypes
{
    const TYPE_PLAIN_TEXT_WITH_CHARSET_UTF8   = 'text/plain; charset=utf-8';
    const TYPE_HTML_WITH_CHARSET_UTF8         = 'text/html; charset=utf-8';
    const TYPE_JSON_WITH_CHARSET_UTF8         = 'application/json; charset=utf-8';
    const TYPE_PLAIN_TEXT   = 'text/plain';
    const TYPE_HTML         = 'text/html';
    const TYPE_JSON         = 'application/json';
    // used for identifying standard request when checking for JSON request or the rest is standard.
    const TYPE_STANDARD         = 'standard';
}