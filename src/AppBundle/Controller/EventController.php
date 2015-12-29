<?php
/**
 * Created by PhpStorm.
 * User: Grigory
 * Date: 21/12/2015
 * Time: 6:20 AM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
        $slug = $this->getParameter('nationbuilder.slug');
        $result = $api->communicate($request, "/api/v1/sites/$slug/pages/events", "GET", array('limit' => 10));

        if($result !== NULL) {

//            \Symfony\Component\VarDumper\VarDumper::dump($result);

            $output['events'] = $result['results'];
            if($result['next'] != null) {
                $output['next'] = "/events?" . parse_url($result['next'])['query'];
            }
            if($result['prev'] != null) {
                $output['prev'] = "/events?" . parse_url($result['prev'])['query'];
            }
        } else {
            $this->get('logger')->info("Could not retrieve events");
        }

        return $this->render('AppBundle:Core:events.html.twig', $output);
    }

    /**
     * @Route("/event/delete/{id}", name="delete_event")
     * @Method("GET")
     */
    public function deleteEventAction(Request $request, $id) {

        $api = $this->get('nationbuilder.api');
        $slug = $this->getParameter('nationbuilder.slug');
        $result = $api->communicate($request, "/api/v1/sites/$slug/pages/events/$id", "DELETE");

        if($result === NULL) {
            $this->get('logger')->info("Could not delete event #$id");
//            return $this->render('AppBundle:Core:index.html.twig', array('location' => 'home'));

//            $this->getSession()->
//            return $this->redirectToRoute('events');
        }

        return $this->redirectToRoute('events');
    }

    /**
     * @Route("/event/create", name="create_event_form")
     * @Method("GET")
     */
    public function newEventFormAction(Request $request) {

        $output = array();

//        $output = array('event' => array(
//            'name' => "thing"
//            ,'title' => "something"
//            ,'headline' => "something will happen"
//            ,'intro' => "woah"
//            ,'start_time' => "2017-02-25T12:00:00+10:00"
//            ,'end_time' => "2017-02-25T15:00:00+10:00"
//        ));

        return $this->render('AppBundle:Events:create.html.twig', $output);
    }

    /**
     * @Route("/event/create", name="create_event")
     * @Method("POST")
     */
    public function newEventAction(Request $request) {

        $params = $request->request->all();

        $send_data = array(
            "status" => "unlisted"
            ,'name' => $params['name']
            ,'title' => $params['title']
            ,'headline' => $params['headline']
            ,'intro' => $params['intro']
            ,'start_time' => $params['start_time']
            ,'end_time' => $params['end_time']
        );

//        $this->get('logger')->info("PARAMS " . json_encode($params));
//        $this->get('logger')->info("SEND " . json_encode($send_data));

        $api = $this->get('nationbuilder.api');
        $slug = $this->getParameter('nationbuilder.slug');
        $result = $api->sendData($request, "/api/v1/sites/$slug/pages/events", array('event' => $send_data), "POST");

        if($result === NULL) {
            $this->get('logger')->info("Error - could not create event");
        }

        return $this->redirectToRoute('events');
    }

    /**
     * @Route("/event/edit/{id}", name="edit_event_form")
     * @Method("GET")
     */
    public function editEventFormAction(Request $request, $id) {

        $api = $this->get('nationbuilder.api');
        $slug = $this->getParameter('nationbuilder.slug');
        $result = $api->communicate($request, "/api/v1/sites/$slug/pages/events/$id", "GET");

        if($result === NULL) {
            return $this->redirectToRoute('events');
        }

        return $this->render('AppBundle:Events:edit.html.twig', array('event' => $result['event']));
    }

    /**
     * @Route("/event/edit/{id}", name="edit_event")
     * @Method("POST")
     */
    public function editEventAction(Request $request, $id) {

        $params = $request->request->all();


//        $this->get('logger')->info("PARAMS ($id) " . json_encode($params));


        return $this->redirectToRoute('events');
//        $api = $this->get('nationbuilder.api');
//        $slug = $this->getParameter('nationbuilder.slug');
//        $result = $api->getData($request->getSession(), $request, "/api/v1/sites/$slug/pages/events/$id");
//
//        if($result === NULL) {
//            return $this->redirectToRoute('events');
//        }
//
//        return $this->render('AppBundle:Events:edit.html.twig', array('event' => $result['event']));
    }

}