<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UserController extends FOSRestController
{
    /**
     * @Rest\Get("/user")
     */
    public function getAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $user;
    }

    /**
     * @Rest\Post("/create_user")
     */
    public function postAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user = new User;
        $username = $request->get('username');
        $email = $request->get('email');
        $password = $passwordEncoder->encodePassword($user, $request->get('password'));
        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $phoneNumber = $request->get('phoneNumber');
        $address = $request->get('address');
        if (
            empty($username) ||
            empty($email) ||
            empty($password) ||
            empty($firstName) ||
            empty($lastName) ||
            empty($phoneNumber) ||
            empty($address)
        ) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPhoneNumber($phoneNumber);
        $user->setAddress($address);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new View("User Added Successfully", Response::HTTP_OK);
    }
    /**
     * @Rest\Delete("/user")
     */
    public function deleteAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $access_tokens = $this->getDoctrine()->getRepository("AppBundle:AccessToken")->findBy([
            'user' => $user->getId()
        ]);
        $refresh_tokens = $this->getDoctrine()->getRepository("AppBundle:RefreshToken")->findBy([
            'user' => $user->getId()
        ]);
        $sn = $this->getDoctrine()->getManager();
        if (!empty($access_tokens)) {
            if ( is_array($access_tokens) ) {
                foreach ($access_tokens as $access_token) {
                    $sn->remove($access_token);
                }
            } else {
                $sn->remove($access_tokens);
            }
            $sn->flush();
        }
        if (!empty($refresh_tokens)) {
            if ( is_array($refresh_tokens) ) {
                foreach ($refresh_tokens as $refresh_token) {
                    $sn->remove($refresh_token);
                }
            } else {
                $sn->remove($refresh_tokens);
            }
            $sn->flush();
        }
        if (empty($user)) {
            return new View("user not found", Response::HTTP_NOT_FOUND);
        } else {
            $sn->remove($user);
            $sn->flush();
        }
        return new View("deleted successfully", Response::HTTP_OK);
    }
}