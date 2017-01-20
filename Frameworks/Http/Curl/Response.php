<?php
namespace PAO\Http\Curl;
/**
 * Parses the response from a Curl request into an object containing
 * the response body and an associative array of headers
 *
 * @package curl
 * @author Sean Huber <shuber@huberry.com>
 **/
class Response
{
    /**
     * An associative array containing the response's headers
     *
     * @var array
     **/
    public $head = array();

    /**
     * The body of the response without the headers block
     *
     * @var string
     **/
    public $body;

    /**
     * Accepts the result of a curl request as a string
     *
     * <code>
     * $response = new Response(curl_exec($curl_handle));
     * echo $response->body;
     * echo $response->headers['Status'];
     * </code>
     *
     * @param string $response
     **/
    public function __construct($head = array(), $body = '')
    {
        $this->head = $head;
        $this->body = $body;
    }

    /**
     * Returns the response body
     *
     * <code>
     * $curl = new Curl;
     * $response = $curl->get('google.com');
     * echo $response;  # => echo $response->body;
     * </code>
     *
     * @return string
     **/
    public function __toString() {
        return $this->body;
    }

}