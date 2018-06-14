<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use MainBundle\Form\UserPersonalDataType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use MainBundle\Form\UserType;

class UserController extends Controller
{
    /**
     * @Route("/admin/show-users/{externalId}", name="show_hotel_users")
     * @Template()
     */
    public function showHotelUsersAction(Request $request, $externalId)
    {
        $manager = $this->getDoctrine()->getManager();
        $hotel = $manager->getRepository("MainBundle:Hotel")->findOneBy(['externalId' => $externalId]);

        $users = $hotel->getUsers();

        foreach ($users as &$userRole) {
            switch ($userRole->getRoles()[0]) {
                case 'ROLE_ADMIN': $role = 'admin';
                    break;
                case 'ROLE_USER': $role = 'role user';
                    break;
                default: $role = 'role user';
                    break;
            }
            $userRole->userRole = $role;
        }
        $userId = $this->getUser()->getId();

        return ['users' => $users ,'externalId' => $externalId, 'userId' => $userId, 'user' => $this->getUser()->getUsername()];
    }

    /**
     * @Route("/admin/edit-role/{externalId}/{userId}", name="edit_role")
     * @Template()
     */
    public function editUserRoleAction(Request $request, $userId, $externalId)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $user = $manager->getRepository('MainBundle:User')->find($userId);
                $user->earseRoles();
                $user->addRole($request->request->get('roles'));

                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('show_hotel_users', ['externalId' => $externalId]);
            }
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/create-user/{externalId}", name="create_user")
     * @Template()
     */
    public function createUserAction(Request $request, $externalId)
    {
        $user = new User();
        $form = $this->createForm(UserPersonalDataType::class, $user);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                $userManager =  $this->get('fos_user.user_manager');
                $user = $userManager->createUser();

                $user->setUsername($request->request->get('username'));
                $user->setEmail($request->request->get('email'));
                $user->setPassword(password_hash($request->request->get('password'), PASSWORD_BCRYPT));
                $user->setEnabled(true);

                /** @var User $user */
                $manager = $this->getDoctrine()->getManager();
                $hotel = $manager->getRepository("MainBundle:Hotel")->findOneBy(['externalId' => $externalId]);

                $user->addHotel($hotel);
                $userManager->updateUser($user);

                return $this->redirectToRoute('show_hotel_users', ['externalId' => $externalId]);
            }
        }


        return ['form' => $form->createView(), 'user' => $this->getUser()->getUsername()];
    }

    /**
     * @Route("/admin/update-user/{userId}/{externalId}", name="update_user")
     * @Template()
     */
    public function updateUserAction(Request $request, $userId, $externalId)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository('MainBundle:User')->find($userId);

        $form = $this->createForm(UserPersonalDataType::class, $user);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $userManager =  $this->get('fos_user.user_manager');

                $user->setUsername($request->request->get('username'));
                $user->setEmail($request->request->get('email'));
                $user->setPassword(password_hash($request->request->get('password'), PASSWORD_BCRYPT));
                $user->setEnabled(true);

                $userManager->updateUser($user);
                return $this->redirectToRoute('show_hotel_users', ['externalId' => $externalId]);
            }
        }

        return ['form' => $form->createView(), 'user' => $this->getUser()->getUsername()];
    }

    /**
     * @Route("/admin/delete/user/{externalId}/{userId}", name="delete_user")
     */
    public function deleteUserAction(Request $request, $externalId, $userId)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager
            ->getRepository('MainBundle:User')
            ->find($userId);

        if (!$user) {
            throw $this->createNotFoundException(
                'No category found for id '. $userId
            );
        }
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('show_hotel_users', ['externalId' => $externalId]);
    }
}
