<?php

namespace MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use MainBundle\Entity\Ticket;
use MainBundle\Form\TicketType;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;


class TicketController extends Controller
{
    /**
     * @Route("/ticket/{hotelUrl}/{locationUrl}", name="check_ticket")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function checkTicketAction(Request $request, $hotelUrl, $locationUrl)
    {

        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);

        return ['form' => $form->createView(), 'user' => $this->getUser()->getUsername(), 'locationUrl' => $locationUrl, 'hotelUrl' => $hotelUrl];
    }

    /**
     * @Route("/get-ticket", name="get_ticket")
     */
    public function getTicketInfoAction(Request $requestFromUser)
    {
        $locationUrl = $requestFromUser->request->get('locationUrl');
        $manager = $this->getDoctrine()->getManager();
        $location = $manager->getRepository('MainBundle:Location')->findOneBy(['url' => $locationUrl]);

        $client = new Client();
        $ticket = $requestFromUser->request->get('ticket');

        $result = $client->request('POST', 'http://orlof.is/midja/interface/product_use_action.php',[
            'form_params' => ['task' => 'check',
                'ticket_number' =>  $ticket,
                'secret_code' => $location->getHotel()->getSecretCode(),
                'seller_number' => $location->getHotel()->getSellerNumber(),
                'ternimal_number' => $location->getLocationName(),
                'global_language' => 8 ]
            ]);

        $body =  (string) $result->getBody();
        $englishResponse = substr($body, 8, strpos($body, '.') - 8);

        switch ($englishResponse) {
            case 'The ticket number has not been assigned or not found in the system':
                $icelandResponse = 'Miðanúmeri hefur ekki verið úthlutað eða finnst ekki í kerfinu. Athugaðu hvort að númerið sé rétt slegið inn.';
                break;
            case 'The ticket number has been used':
                $icelandResponse = 'Miðanúmerið hefur verið notað.';
                $getResult = $client->request('POST', 'http://orlof.is/midja/interface/product_use_action.php',[
                    'form_params' => ['task' => 'get',
                        'ticket_number' =>  $ticket,
                        'global_language' => 8,
                    ]
                ]);
                $getBody =  (string) $getResult->getBody();
                $dataArray = json_decode($getBody, true);

                if(is_null($dataArray['used_time']) || $dataArray['used_time'] == '0000-00-00'){
                    $date = '';
                } else{
                    $date = (string) (new \DateTime($dataArray['used_time']))->format('d.m.Y h:i:s') . ' ';
                }

                $icelandResponse .= '<br />' . $date . (string) $dataArray['terminal_number'];
                break;
            case 'The ticket number has been assigned but not used':
                $icelandResponse = 'Miðanúmeri hefur verið úthlutað en ekki notað.';
                break;
            case 'et is invalid&title=Erro':
                $icelandResponse = 'Miðanúmeri hefur ekki verið úthlutað eða finnst ekki í kerfinu. Athugaðu hvort að númerið sé rétt slegið inn';
                break;
            default: $icelandResponse = $body;
                break;
        }

        echo $icelandResponse;
        exit;
    }

    /**
     * @Route("use-ticket", name="use_ticket")
     */
    public function useTicketAction(Request $requestFromUser)
    {
        $locationUrl = $requestFromUser->request->get('locationUrl');
        $manager = $this->getDoctrine()->getManager();
        $location = $manager->getRepository('MainBundle:Location')->findOneBy(['url' => $locationUrl]);

        $client = new Client();
        $ticket = $requestFromUser->request->get('ticket');
        $result = $client->request('POST', 'http://orlof.is/midja/interface/product_use_action.php',[
            'form_params' => ['task' => 'use',
                'ticket_number' =>  $ticket,
                'secret_code' => $location->getHotel()->getSecretCode(),
                'seller_number' => $location->getHotel()->getSellerNumber(),
                'ternimal_number' => $location->getLocationName(),
                'global_language' => 8 ]
        ]);

        $body =  (string) $result->getBody();
        $englishResponse = substr($body, 8, strpos($body, '.') - 8);

        switch ($englishResponse) {
            case 'Ticket fail to use':
                $icelandResponse = 'Miðanúmeri hefur ekki verið úthlutað eða finnst ekki í kerfinu. Athugaðu hvort að númerið sé rétt slegið inn.';
                break;
            case 'Ticket use successfully':
                $icelandResponse = 'Miðanúmeri hefur verið úthlutað en ekki notað.';
                break;
            default: $icelandResponse = $body;
                break;
        }

        echo $icelandResponse;
        exit;
    }

    /**
     * @Route("/{hotelUrl}/{locationUrl}", name="nologin_url")
     */
    public function noLoginEnterAction($hotelUrl, $locationUrl)
    {
        $manager = $this->getDoctrine()->getManager();

        $hotel = $manager->getRepository('MainBundle:Hotel')->findOneBy(['url' => $hotelUrl ]);
        $location = $manager->getRepository('MainBundle:Location')->findOneBy(['url' => $locationUrl ]);

        if (null === $hotel || null === $location) {
           return $this->redirect('/login');
        }

        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);

        return $this->render('MainBundle:Ticket:checkTicket.html.twig',
            ['form' => $form->createView(), 'user' => '', 'locationUrl' => $locationUrl, 'hotelUrl' => $hotelUrl]);
    }
}
