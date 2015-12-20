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
        return $this->render('AppBundle:Core:events.html.twig', array('location' => 'events'));
    }

}