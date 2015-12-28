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
use Symfony\Component\HttpFoundation\RedirectResponse;

use OAuth2\Client;

class CoreController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {

        return $this->render('AppBundle:Core:index.html.twig', array('location' => 'home'));
    }

    /**
     * @Route("/oauth", name="oauth")
     */
    public function oauthAction(Request $request) {

        $client_test_token = $this->getParameter('oauth.test_token');
        if($client_test_token != NULL) {

            $this->get('logger')->info("Authenticating with test token");

            $session = $request->getSession();
            $session->set('oauth_token', $client_test_token);

            return $this->redirectToRoute('homepage');
        }

        $client_id = $this->getParameter('oauth.client_id');
        $client_secret = $this->getParameter('oauth.client_secret');
        $base_url = $this->getParameter('oauth.base_url');
        $send_url = $base_url . '/oauth/authorize';

        $client = new Client($client_id, $client_secret);
        $redirectUrl = $request->getSchemeAndHttpHost() . $this->get('router')->generate('oauth_callback');

        $authUrl = $client->getAuthenticationUrl($send_url, $redirectUrl);

        $this->get('logger')->info("Redirecting to $authUrl");

        return new RedirectResponse($authUrl);
    }

    /**
     * @Route("/oauth_callback", name="oauth_callback")
     */
    public function oauthCallbackAction(Request $request) {

        $this->get('logger')->info("Redirected successfully");

        $input_query = $request->query->all();
        if(array_key_exists('code', $input_query)) {
            $session = $request->getSession();
            $session->set('oauth_token', $input_query['code']);
            $this->get('logger')->info("Oauth token set successfully");
        }

        return $this->redirectToRoute('homepage');
    }
}
