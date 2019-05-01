<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
#use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Author;
use App\Entity\Quote;

class QuoteController extends FOSRestController
{
	private $user;

	const STATUS_OK = 'OK';
	const STATUS_ERROR = 'Error';

	public function __construct(Security $security)
	{
		$this->user = $security->getUser();
	}

	/**
	* Returns all quotes for current user
	* @Rest\Get("/quote")
	*/
	public function getQuotes(Security $security)
	{
		$quotes = $this->getDoctrine()->getRepository(Quote::class)->findBy(['user' => $this->user]);

		$view = $this->view(array(
			'status' => self::STATUS_OK,
			'data' => $quotes
		), Response::HTTP_OK);
		return $this->handleView($view);
	}

	/**
	* Create new quote
	* @Rest\Post("/quote")
	*/
	public function addNewQuoteAction(Request $request)
	{
		$quote_author = trim($request->get('author'));
		$text = trim($request->get('text'));

		if (empty($quote_author) || empty($text))
		{
			$view = $this->view(array('status' => self::STATUS_ERROR, 'message' => 'Either author or quote text is blank'), Response::HTTP_NOT_ACCEPTABLE);
			return $this->handleView($view);
		}

		$em = $this->getDoctrine()->getManager();

		$author = $this->getDoctrine()->getRepository(Author::class)->findOneBy(['name' => $quote_author]);
		if (!$author)
		{
			$author = new Author();
			$author->setName($quote_author);
			$em->persist($author);
		}

		$quote = new Quote();
		$quote->setAuthor($author);
		$quote->setText($text);
		$quote->setUser($this->user);
		$em->persist($quote);
		$em->flush();

		$view = $this->view(array('status' => self::STATUS_OK, 'message' => 'Quote Added Successfully'), Response::HTTP_OK);
		return $this->handleView($view);
	}


	/**
	* Update quote
	* @Rest\Post("/quote/{id}")
	*/
	public function updateQuoteAction(Request $request, $id)
	{
		$quote_author = trim($request->get('author'));
		$text = trim($request->get('text'));

		if (empty($quote_author) || empty($text))
		{
			$view = $this->view(array('status' => self::STATUS_ERROR, 'message' => 'Either author or quote text is blank'), Response::HTTP_NOT_ACCEPTABLE);
			return $this->handleView($view);
		}

		$quote = $this->getDoctrine()->getRepository(\App\Entity\Quote::class)->find($id);
		if (empty($quote) || $quote->getUser()->getId() != $this->user->getId())
		{
			$view = $this->view(array('status' => self::STATUS_ERROR, 'message' => 'Quote not found'), Response::HTTP_NOT_FOUND);
		}
		else
		{
			$em = $this->getDoctrine()->getManager();

			$author = $this->getDoctrine()->getRepository(Author::class)->findOneBy(['name' => $quote_author]);
			if (!$author)
			{
				$author = new Author();
				$author->setName($quote_author);
				$em->persist($author);
			}

			$quote->setAuthor($author);
			$quote->setText($text);
			$em->persist($quote);
			$em->flush();

			$view = $this->view(array('status' => self::STATUS_OK, 'message' => 'Quote updated'), Response::HTTP_OK);
		}

		return $this->handleView($view);
	}

	/**
	* Delete quote
	* @Rest\Delete("/quote/{id}")
	*/
	public function deleteQuoteAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$quote = $this->getDoctrine()->getRepository(\App\Entity\Quote::class)->find($id);
		if (empty($quote) || $quote->getUser()->getId() != $this->user->getId())
		{
			$view = $this->view(array('status' => self::STATUS_ERROR, 'message' => 'Quote not found'), Response::HTTP_NOT_FOUND);
		}
		else
		{
			$em->remove($quote);
			$em->flush();
			$view = $this->view(array('status' => self::STATUS_OK, 'message' => 'Quote removed'), Response::HTTP_OK);
		}

		return $this->handleView($view);
	}
}