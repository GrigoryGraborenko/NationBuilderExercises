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

class EventController extends Controller {

    /**
     * @Route("/events", name="events")
     */
    public function indexAction(Request $request) {

        $output = array('location' => 'events');

        $output['users'] = array();
        $output['next'] = null;
        $output['prev'] = null;

        $api = $this->get('nationbuilder.api');
        $result = $api->getData($request->getSession(), $request, '/api/v1/sites/peopledecide/pages/events', array('limit' => 10));

        if($result !== NULL) {

            \Symfony\Component\VarDumper\VarDumper::dump($result);

            $output['events'] = $result['results'];
            if($result['next'] != null) {
                $output['next'] = "/events?" . parse_url($result['next'])['query'];
            }
            if($result['prev'] != null) {
                $output['prev'] = "/events?" . parse_url($result['prev'])['query'];
            }
        }

        return $this->render('AppBundle:Core:events.html.twig', $output);
    }

}