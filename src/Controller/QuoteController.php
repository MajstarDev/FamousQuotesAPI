<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
#use App\Entity\User;

class QuoteController extends FOSRestController
{
	/**
	* Creates new API key
	* @Rest\Get("/quote")
	*/
	public function getQuotes()
	{
		$view = $this->view(array('hey' => 'this url is protected'), 200);
		return $this->handleView($view);
	}
}