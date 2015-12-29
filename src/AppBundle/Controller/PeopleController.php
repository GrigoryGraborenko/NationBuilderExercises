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

    /**
     * @Route("/people/delete/{id}", name="delete_person")
     * @Method("GET")
     */
    public function deletePersonAction(Request $request, $id) {

        $api = $this->get('nationbuilder.api');
        $result = $api->communicate($request, "/api/v1/people/$id", "DELETE");

        if($result === NULL) {
            $this->get('logger')->info("Could not delete person #$id");
        }

        return $this->redirectToRoute('people');
    }

    /**
     * @Route("/people/create", name="create_person_form")
     * @Method("GET")
     */
    public function newPersonFormAction(Request $request) {

        $output = array();

        return $this->render('AppBundle:People:create.html.twig', $output);
    }

    /**
     * @Route("/people/create", name="create_person")
     * @Method("POST")
     */
    public function newPersonAction(Request $request) {

        $params = $request->request->all();

        $send_data = array(
            'first_name' => $params['first_name']
            ,'last_name' => $params['last_name']
            ,'email' => $params['email']
        );

        $api = $this->get('nationbuilder.api');
        $result = $api->sendData($request, "/api/v1/people", array('person' => $send_data), "POST");

        if($result === NULL) {
            $this->get('logger')->info("Error - could not create person");
        }

        return $this->redirectToRoute('people');
    }

    /**
     * @Route("/people/edit/{id}", name="edit_person_form")
     * @Method("GET")
     */
    public function editPersonFormAction(Request $request, $id) {

        $api = $this->get('nationbuilder.api');
        $result = $api->communicate($request, "/api/v1/people/$id", "GET");

        if($result === NULL) {
            return $this->redirectToRoute('people');
        }

        return $this->render('AppBundle:People:edit.html.twig', array('person' => $result['person']));
    }

    /**
     * @Route("/people/edit/{id}", name="edit_person")
     * @Method("POST")
     */
    public function editPersonAction(Request $request, $id) {

        $params = $request->request->all();

        $send_data = array(
            'first_name' => $params['first_name']
            ,'last_name' => $params['last_name']
        );

        $api = $this->get('nationbuilder.api');
        $result = $api->sendData($request, "/api/v1/people/$id", array('person' => $send_data), "PUT");

        if($result === NULL) {
            $this->get('logger')->info("Error - could not update person");
            return $this->redirectToRoute('edit_person_form', array('id' => $id));
        }

        return $this->redirectToRoute('people');
    }

}