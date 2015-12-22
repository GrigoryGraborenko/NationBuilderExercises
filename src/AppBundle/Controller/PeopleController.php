<?php
/**
 * Created by PhpStorm.
 * User: Grigory
 * Date: 21/12/2015
 * Time: 6:20 AM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use OAuth2\Client;

/**
 * Class PeopleController
 * @package AppBundle\Controller
 */
class PeopleController extends Controller {

    /**
     * @Route("/people", name="people")
     */
    public function indexAction(Request $request) {

        $session = $request->getSession();

        $output = array('location' => 'people');

        $output['users'] = array();
        $output['next'] = null;
        $output['prev'] = null;

        if($session->has('oauth_token')) {

            $token = $session->get('oauth_token');

            $base_url = $this->getParameter('oauth.base_url');
            $client_id = $this->getParameter('oauth.client_id');
            $client_secret = $this->getParameter('oauth.client_secret');
            $send_url = $base_url . '/api/v1/people';
            $send_query = array('limit' => 10);

            // if paginating, pass along tokens to request
            $input_query = $request->query->all();
            if(array_key_exists('__nonce', $input_query) && array_key_exists('__token', $input_query)) {
                $send_query['__nonce'] = $input_query['__nonce'];
                $send_query['__token'] = $input_query['__token'];
            }

            $client = new Client($client_id, $client_secret);
            $client->setAccessToken($token);
            $response = $client->fetch($send_url, $send_query);

            if($response['code'] == '200') {
                $result = $response['result'];

                $output['users'] = $result['results'];

                if($result['next'] != null) {
                    $output['next'] = "/people?" . parse_url($result['next'])['query'];
                }
                if($result['prev'] != null) {
                    $output['prev'] = "/people?" . parse_url($result['prev'])['query'];
                }
            }
        }

//        \Symfony\Component\VarDumper\VarDumper::dump($output);

        return $this->render('AppBundle:Core:people.html.twig', $output);
    }

}