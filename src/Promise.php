<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\HttpClient;

use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Promise
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Promise
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var PromiseInterface
     */
    private $promise;

    /**
     * Promise constructor.
     * @param PromiseInterface $promise
     * @param Response $response
     */
    public function __construct(PromiseInterface &$promise, Response &$response)
    {
        $this->promise = $promise;
        $this->response = $response;
    }

    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->promise->getState() != 'pending';
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function wait()
    {
        try {
            $res = $this->promise->wait();
            if ($res instanceof ResponseInterface) {
                $this->response->parseResponse($res->getStatusCode(), $res->getBody());
            }
        } catch (TransferException $e) {
            $message = $e->getMessage();
            if ($e->hasResponse()) {
                $message = $e->getResponse()->getBody();
            }
            $this->response->parseErrorResponse($e->getCode(), $message);
        }
        return $this->response;
    }
}