<?php

namespace Webmax\VelmaClient\Model;

/**
 * Velma response model
 *
 * @author Frank Bardon Jr. <frankbardon@gmail.com>
 * @todo Fully unit test.
 */
class VelmaResponse
{
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILURE = 'failure';

    protected $code;
    protected $status;
    protected $message;
    protected $jobId;

    /**
     * Test if response was successful
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return self::STATUS_SUCCESS === $this->status;
    }

    /**
     * Test if response was a failure
     *
     * @return boolean
     */
    public function isFailure()
    {
        return self::STATUS_FAILURE === $this->status;;
    }

    /**
     * Get response code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get response status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get response message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get job id
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

}
