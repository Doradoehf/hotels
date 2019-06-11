<?php

namespace MainBundle\Controller;

use MainBundle\Entity\Hotel;
use MainBundle\Entity\Location;
use MainBundle\Form\HotelType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

class HotelController extends Controller
{
    /**
     * @Route("/admin/show-hotel", name="show_hotel")
     * @Template()
     */
    public function showAllHotelsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();


        $client = new Client();
        $result = $client->request('POST', 'https://orlof.is/midja/interface/product_use_action.php', [
            'form_params' => ['task' => 'getHotels']
        ]);

        $hotels = json_decode($result->getBody(), true);
        $availableExternalId = $this->getDataFromApi($hotels);

        $user = $this->getUser()->getUsername();
        $this->deleteDiff($availableExternalId);

        $hotels = $manager->getRepository('MainBundle:Hotel')->findAll();

        return ['hotels' => $hotels, 'user' => $user];
    }

    public function getDataFromApi($hotels)
    {
        $manager = $this->getDoctrine()->getManager();

        $availableExternalId = [];
        foreach ($hotels as $hotelOne) {
            $hotel = new Hotel();
            $hotel->setName($hotelOne['name']);
            $hotel->setUrl($hotelOne['seller_no'] . $hotelOne['secretcode']);
            $hotel->setExternalId($hotelOne['seller_id']);
            $hotel->setSecretCode($hotelOne['secretcode']);
            $hotel->setSellerNumber($hotelOne['seller_no']);
            $isHotel = $manager->getRepository('MainBundle:Hotel')->isHotelExist($hotelOne['seller_id']);
            if (!$isHotel) {
                $manager->persist($hotel);

                $location = new Location();
                $location->setLocationName($hotel->getName());
                $location->setUrl($hotel->getUrl());
                $location->setHotel($hotel);

                $manager->persist($location);
                $manager->flush();
            }

            $availableExternalId[] = (int)$hotelOne['seller_id'];
        }

        $manager->flush();

        return $availableExternalId;
    }

    public function deleteDiff($availableExternalId)
    {
        $manager = $this->getDoctrine()->getManager();

        $externalId = $manager->getRepository('MainBundle:Hotel')->findExternalId();
        $existExternalId = [];
        foreach ($externalId as $id) {
            $existExternalId[] = $id['externalId'];
        }
        $diff = array_diff($existExternalId, $availableExternalId);

        /*if (!empty($diff)) {
            foreach ($diff as $hotelExternalId) {
                $hotelForDelete = $manager
                    ->getRepository('MainBundle:Hotel')
                    ->findOneBy(['externalId' => $hotelExternalId]);
                $manager->remove($hotelForDelete);
            }
            $manager->flush();
        }*/
    }

    /**
     * @Route("manager/show-hotel", name="show_one_hotel")
     * @Template()
     */
    public function showOneHotelByManagerAction()
    {

        $manager = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getId();

        $user = $manager->getRepository("MainBundle:User")->find($userId);
        $hotel = $user->getHotels();


        return ['hotel' => $hotel, 'user' => $this->getUser()->getUsername()];

    }
}
