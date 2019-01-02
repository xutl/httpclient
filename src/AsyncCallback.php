<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace XuTL\HttpClient;


/**
 * Class AsyncCallback
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class AsyncCallback
{
    /**
     * @var callable
     */
    protected $succeedCallback;

    /**
     * @var callable
     */
    protected $failedCallback;

    /**
     * AsyncCallback constructor.
     * @param callable $succeedCallback
     * @param callable $failedCallback
     */
    public function __construct(callable $succeedCallback, callable $failedCallback)
    {
        $this->succeedCallback = $succeedCallback;
        $this->failedCallback = $failedCallback;
    }

    /**
     * @param Response $result
     * @return mixed
     */
    public function onSucceed(Response $result)
    {
        return call_user_func($this->succeedCallback, $result);
    }

    /**
     * @param \Exception $e
     * @return mixed
     */
    public function onFailed(\Exception $e)
    {
        return call_user_func($this->failedCallback, $e);
    }
}