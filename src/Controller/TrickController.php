<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
	/**
	 * @Route("/{slug}", name="category_show", priority=-1)
	 */
	public function category($slug, CategoryRepository $categoryRepository): Response
	{
		$category = $categoryRepository->findOneBy([
			'slug' => $slug,
		]);

		if (!$category) {
			throw $this->createNotFoundException("La catégorie demandée n'existe pas.");
		}
		return $this->render('trick/category.html.twig', [
			'slug' => $slug,
			'category' => $category,
		]);
	}

	/**
	 * @Route("{category_slug}/{slug}", name="trick_show", priority=-1)
	 */
	public function show($slug, TrickRepository $trickRepository)
	{
		$trick = $trickRepository->findOneBy([
			'slug' => $slug,
		]);

		if (!$trick) {
			throw $this->createNotFoundException("Le produit demandé n'existe pas.");
		}

		return $this->render('trick/show.html.twig', [
			'trick' => $trick,
		]);
	}
}
