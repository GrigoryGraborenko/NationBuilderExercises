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

class PeopleController extends Controller {

    /**
     * @Route("/people", name="people")
     */
    public function indexAction(Request $request) {
        return $this->render('AppBundle:Core:people.html.twig', array('location' => 'people'));
    }

}