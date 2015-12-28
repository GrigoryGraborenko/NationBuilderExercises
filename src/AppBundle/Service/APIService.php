<?php
/**
 * Created by PhpStorm.
 * User: Grigory
 * Date: 23/12/2015
 * Time: 6:31 AM
 */

namespace AppBundle\Service;

use OAuth2\Client;


/**
 * Class APIService
 * @package AppBundle\Service
 */
class APIService {

    private $logger;
    private $client_id;
    private $client_secret;
    private $base_url;
    private $test_token;

    /**
     * APIService constructor.
     */
    public function __construct($logger, $client_id, $client_secret, $base_url, $test_token) {
        $this->logger = $logger;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->base_url = $base_url;
        $this->test_token = $test_token;
    }

    /**
     * Gets/sends data from the nation builder site
     *
     * @param $session
     * @param $request
     * @param $api_call
     * @param $params
     * @return null
     */
    public function getData($session, $request, $api_call, $params) {

        if(!$session->has('oauth_token')) {
            $this->logger->info("No oauth token found");
            return NULL;
        }
        $token = $session->get('oauth_token');

        $send_url = $this->base_url . $api_call;

        // if paginating, pass along tokens to request
        $input_query = $request->query->all();
        if(array_key_exists('__nonce', $input_query) && array_key_exists('__token', $input_query)) {
            $params['__nonce'] = $input_query['__nonce'];
            $params['__token'] = $input_query['__token'];
        }

        $client = new Client($this->client_id, $this->client_secret);
        $client->setAccessToken($token);
        $response = $client->fetch($send_url, $params);

        if($response['code'] === '200') {
            $this->logger->info("Could not retrieve data. Response: " . json_encode($response));
            return NULL;
        }
        return $response['result'];
    }

}
