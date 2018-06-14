<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Location;
use MainBundle\Form\LocationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LocationController extends Controller
{
    /**
     * @Route("/create-location/{hotelUrl}", name="create_location")
     * @Template()
     */
    public function createLocationAction(Request $request, $hotelUrl)
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $hotel = $manager->getRepository('MainBundle:Hotel')->findOneBy(['url' => $hotelUrl]);
                $location->setHotel($hotel);
                $manager->persist($location);
                $manager->flush();
                return $this->redirectToRoute('show_locations', ['hotelUrl' => $hotelUrl]);
            }
        }
        return ['form' => $form->createView(), 'user' => $this->getUser()->getUsername(), 'hotelUrl' => $hotelUrl];
    }

    /**
     * @Route("/show-locations/{hotelUrl}", name="show_locations")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showHotelLocationAction(Request $request, $hotelUrl)
    {
        $manager = $this->getDoctrine()->getManager();
        $hotelId = $manager
            ->getRepository('MainBundle:Hotel')
            ->findOneBy(['url' => $hotelUrl]);

        $locations = $manager
            ->getRepository('MainBundle:Location')
            ->findHotelLocations($hotelId);

        $baseUrl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        return ['locations' => $locations, 'hotelUrl' => $hotelUrl, 'user' => $this->getUser()->getUsername(), 'baseUrl' => $baseUrl];
    }

    /**
     * @Route("edit/location/{hotelUrl}/{locationUrl}", name="edit_location")
     * @Template()
     */
    public function editLocationAction(Request $request, $locationUrl, $hotelUrl)
    {
        $manager = $this->getDoctrine()->getManager();
        $location = $manager
            ->getRepository('MainBundle:Location')
            ->findOneBy(['url' => $locationUrl]);

        if (!$location) {
            throw $this->createNotFoundException(
                'No location found for ' . $locationUrl
            );
        }
        $form = $this->createForm(LocationType::class, $location);
        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($location);
                $manager->flush();

                return $this->redirectToRoute('show_locations', array('hotelUrl' => $hotelUrl));
            }
        }

        return ['form' => $form->createView(), 'user' => $this->getUser()->getUsername()];
    }

    /**
     * @Route("delete/location/{hotelUrl}/{locationUrl}", name="delete_location")
     */
    public function deleteLocationAction(Request $request, $locationUrl, $hotelUrl)
    {
        $manager = $this->getDoctrine()->getManager();
        $location = $manager
            ->getRepository('MainBundle:Location')
            ->findOneBy(['url' => $locationUrl]);

        if (!$location) {
            throw $this->createNotFoundException(
                'No location found for  ' . $locationUrl
            );
        }
        $manager->remove($location);
        $manager->flush();

        return $this->redirectToRoute('show_locations', array('hotelUrl' => $hotelUrl));
    }

    /**
     * @Route("/user/select-location", name="select_location")
     * @Template()
     */
    public function selectLocationByUserAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getId();

        $user = $manager->getRepository("MainBundle:User")->find($userId);
        $hotels = $user->getHotels();

        $locations = $manager->getRepository('MainBundle:Location')->findHotelLocations($hotels->first()->getId());
        $hotel = $manager->getRepository('MainBundle:Hotel')->find($hotels->first()->getId());

        $hotelUrl = $hotel->getUrl();
        $baseUrl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        return ['user' => $this->getUser()->getUsername(), 'hotels' => $hotels, 'hotelUrl' => $hotelUrl,'locations' => $locations, 'baseUrl' => $baseUrl];
    }

    /**
     * @Route("/create-location-user/{hotelUrl}", name="create_location_user")
     * @Template()
     */
    public function createLocationUserAction(Request $request, $hotelUrl)
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $hotel = $manager->getRepository('MainBundle:Hotel')->findOneBy(['url' => $hotelUrl]);
                $location->setHotel($hotel);
                $manager->persist($location);
                $manager->flush();
                return $this->redirectToRoute('select_location');
            }
        }
        return ['form' => $form->createView(), 'user' => $this->getUser()->getUsername(), 'hotelUrl' => $hotelUrl];
    }
}
