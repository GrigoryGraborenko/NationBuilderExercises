<?php
/**
 * Created by PhpStorm.
 * User: Grigory
 * Date: 20/12/2015
 * Time: 7:45 AM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use OAuth2\Client;
use OAuth2\GrantType\IGrantType;
use OAuth2\GrantType\AuthorizationCode;

class CoreController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {

        /*
        $client_id = $this->getParameter('oauth.client_id');
        $client_secret = $this->getParameter('oauth.client_secret');
        $client_test_token = $this->getParameter('oauth.test_token');
        $base_url = $this->getParameter('oauth.base_url');

        $client = new Client($client_id, $client_secret);

        $client->setAccessToken($client_test_token);
        $response = $client->fetch($base_url . '/api/v1/people');

        \Symfony\Component\VarDumper\VarDumper::dump($response);
        */

        /*
        $client = new Client(CLIENT_ID, CLIENT_SECRET);
        if (!isset($_GET['code'])) {

//            $auth_url = $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI);
//            header('Location: ' . $auth_url);
//            die('Redirect');

        } else {

//            $params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
//            $response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params);
//            parse_str($response['result'], $info);
//            $client->setAccessToken($info['access_token']);
//            $response = $client->fetch('https://graph.facebook.com/me');
//            var_dump($response, $response['result']);
            
        }*/

        // replace this example code with whatever you need
        return $this->render('AppBundle:Core:index.html.twig', array('location' => 'home'));
    }
}
