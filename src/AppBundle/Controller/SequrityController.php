<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class SequrityController extends FOSRestController
{
    /**
     * @Rest\Post("/login")
     */
    public function loginAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $login = $request->get('login');
        $password = $request->get('password');
        $user = $this
                ->getDoctrine()
                ->getRepository("AppBundle:User")
                ->findOneBy([
                    'username' => $login
                ]);
        if (!$user) {
            $user = $this
                ->getDoctrine()
                ->getRepository("AppBundle:User")
                ->findOneBy([
                    'email' => $login
                ]);
        }
        if(!$user) {
            return new View('User not found',Response::HTTP_NOT_FOUND);
        }

        $isValid = $passwordEncoder
            ->isPasswordValid($user, $password);

        if (!$isValid) {
            return new View('User password not correct',Response::HTTP_NOT_FOUND);
        }
        return $user;
    }
}