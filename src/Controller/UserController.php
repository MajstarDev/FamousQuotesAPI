<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;

class UserController extends FOSRestController
{
	/**
	* Creates new API key
	* @Rest\Get("/key")
	*/
	public function getNewApiKey()
	{
		$user = new User();
		$rand_hash = sha1(random_bytes(16));
		$user->setAccessKey($rand_hash);

		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		$view = $this->view(array('status' => QuoteController::STATUS_OK, 'key' => $user->getAccessKey()), Response::HTTP_OK);
		return $this->handleView($view);
	}
}