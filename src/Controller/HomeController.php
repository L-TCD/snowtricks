<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
	/**
	 * show homepage
	 * @Route("/", name="home")
	 */
	public function index(TrickRepository $trickRepository): Response
	{
		return $this->render('home/index.html.twig', [
			'controller_name' => 'HomeController',
			'tricks' => $trickRepository->findAll(),
		]);
	}

	/**
	 * @Route("/admin", name="admin_home")
	 */
	public function adminHome(): Response
	{
		return $this->render('home/admin_home.html.twig');
	}
}
