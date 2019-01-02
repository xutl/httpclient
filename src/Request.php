<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\HttpClient;


/**
 * Class Request
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Request
{
    /**
     * @var string request method.
     */
    private $_method = 'GET';

    /**
     * @param string $method request method
     * @return $this self reference.
     */
    public function setMethod($method)
    {
        $this->_method = $method;
        return $this;
    }

    /**
     * @return string request method
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * @return string
     */
    public function getUri()
    {

    }

    /**
     * @return array
     */
    public function getHeaders()
    {

    }

    public function getBody(){

    }

    public function getQueryString(){

    }
}