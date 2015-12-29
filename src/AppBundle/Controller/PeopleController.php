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

/**
 * Class PeopleController
 * @package AppBundle\Controller
 */
class PeopleController extends Controller {

    /**
     * @Route("/people", name="people")
     */
    public function indexAction(Request $request) {

        $output = array('location' => 'people');

        $output['users'] = array();
        $output['next'] = null;
        $output['prev'] = null;

        $api = $this->get('nationbuilder.api');
        $result = $api->communicate($request, '/api/v1/people', "GET", array('limit' => 10));

        if($result !== NULL) {

            $output['users'] = $result['results'];
            if($result['next'] != null) {
                $output['next'] = "/people?" . parse_url($result['next'])['query'];
            }
            if($result['prev'] != null) {
                $output['prev'] = "/people?" . parse_url($result['prev'])['query'];
            }
        }
//        \Symfony\Component\VarDumper\VarDumper::dump($output);

        return $this->render('AppBundle:Core:people.html.twig', $output);
    }

}