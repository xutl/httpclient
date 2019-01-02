<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\HttpClient;

use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Client constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->client = new \GuzzleHttp\Client($config);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param AsyncCallback|NULL $callback
     * @return Promise
     * @throws Exception
     */
    public function sendRequestAsync(Request $request, Response &$response, AsyncCallback $callback = null)
    {
        $promise = $this->sendRequestAsyncInternal($request, $response, $callback);
        return new Promise($promise, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     */
    public function sendRequest(Request $request, Response &$response)
    {
        $promise = $this->sendRequestAsync($request, $response);
        return $promise->wait();
    }

    /**
     * 请求前处理
     * @param Request $request
     */
    public function beforeRequest(Request &$request)
    {

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param AsyncCallback|NULL $callback
     * @return \GuzzleHttp\Promise\PromiseInterface
     * @throws Exception
     */
    private function sendRequestAsyncInternal(Request &$request, Response &$response, AsyncCallback $callback = null)
    {
        $this->beforeRequest($request);
        $parameters = ['exceptions' => false, 'http_errors' => false];
        $queryString = $request->getQueryString();
        $body = $request->getBody();
        if ($queryString != null) {
            $parameters['query'] = $queryString;
        }
        if ($body != null) {
            $parameters['body'] = $body;
        }

        //$parameters['timeout'] = $this->requestTimeout;
        //$parameters['connect_timeout'] = $this->connectTimeout;

        $request = new \GuzzleHttp\Psr7\Request(strtoupper($request->getMethod()), $request->getUri(), $request->getHeaders());
        try {
            if ($callback != null) {
                return $this->client->sendAsync($request, $parameters)->then(
                    function (ResponseInterface $res) use (&$response, $callback) {
                        try {
                            $response->parseResponse($res->getStatusCode(), $res->getBody());
                            $callback->onSucceed($response);
                        } catch (Exception $e) {
                            $callback->onFailed($e);
                        }
                    }
                );
            } else {
                return $this->client->sendAsync($request, $parameters);
            }
        } catch (TransferException $e) {
            throw new Exception($e->getCode(), $e->getMessage(), $e);
        }
    }
}