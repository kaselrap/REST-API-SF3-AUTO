<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\Cars;

class CarsController extends FOSRestController
{
    /**
     * @Rest\Get("/cars/")
     */
    public function getAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $carsresult = $this->getDoctrine()->getRepository("AppBundle:Cars")->findBy([
           'userId' => $user->getId()
        ]);
        if ($carsresult === null) {
            return new View('there are no cars exist', Response::HTTP_NOT_FOUND);
        }
        return $carsresult;
    }

    /**
     * @Rest\Get("/cars/{id}")
     */
    public function idAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $carreslt = $this->getDoctrine()->getRepository("AppBundle:Cars")->findOneBy([
            'id' => $id,
            'userId' => $user->getId()
        ]);
        if ($carreslt === null) {
            return new View('there are no cars exist', Response::HTTP_NOT_FOUND);
        }
        return $carreslt;
    }

    /**
     * @Rest\Post("/cars/")
     */
    public function postAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cars = new Cars;
        $model = $request->get('model');
        $number = $request->get('number');
        $yearOfIssue = $request->get('yearOfIssue');
        $insurancePolicyNumber = $request->get('insurancePolicyNumber');
        $userId = $user->getId();

        if (
            empty($model) ||
            empty($number) ||
            empty($yearOfIssue) ||
            empty($insurancePolicyNumber)
        ) {
            return new View("NULL VALUES ARE NOT ALLOWED", Response::HTTP_NOT_ACCEPTABLE);
        }
        $cars->setModel($model);
        $cars->setNumber($number);
        $cars->setYearOfIssue($yearOfIssue);
        $cars->setInsurancePolicyNumber($insurancePolicyNumber);
        $cars->setUserId($userId);
        $em = $this->getDoctrine()->getManager();
        $em->persist($cars);
        $em->flush();
        return new View("Car Added Successfully", Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/cars/{id}")
     */
    public function deleteAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $sn = $this->getDoctrine()->getManager();
        $car = $this->getDoctrine()->getRepository("AppBundle:Cars")->findOneBy([
            'id' => $id,
            'userId' => $user->getId()
        ]);
        if (empty($car)) {
            return new View('car not found', Response::HTTP_NOT_FOUND);
        }
        $sn->remove($car);
        $sn->flush();
        return new View('car was deleted successfully', Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/cars/{id}")
     */
    public function updateAction($id, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getManager();
        $car = $this->getDoctrine()->getRepository("AppBundle:Cars")->findOneBy([
            'id' => $id,
            'userId' => $user->getId()
        ]);
        if (empty($car)) {
            return new View('car not found', Response::HTTP_NOT_FOUND);
        }
        $model = (!empty($request->get('model'))) ? $request->get('model') : $car->getModel();
        $number = (!empty($request->get('number'))) ? $request->get('number') : $car->getNumber();
        $yearOfIssue = (!empty($request->get('yearOfIssue')))
            ?
            $request->get('yearOfIssue')
            :
            $car->getYearOfIssue();

        $insurancePolicyNumber = (!empty($request->get('insurancePolicyNumber')))
            ?
            $request->get('insurancePolicyNumber')
            :
            $car->getInsurancePolicyNumber();
        $car->setModel($model);
        $car->setNumber($number);
        $car->setYearOfIssue($yearOfIssue);
        $car->setInsurancePolicyNumber($insurancePolicyNumber);

        $em->flush();
        return new View("Car were updated Successfully", Response::HTTP_OK);
    }
}