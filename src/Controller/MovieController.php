<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MovieController extends Controller
{
	/**
     * @Route("/movies", name="movies")
	 * @Method("GET")
     */
	 public function index(MovieRepository $movieRepository)
	 {
		 $movie = $movieRepository->transformAll();
		 return $this->respond($movie);
	 }

	 /**
     * @Route("/movies/{id}")
	 * @Method("GET")
     */
	public function show($id, MovieRepository $movieRepository)
	{
		$movie = $movieRepository->find($id);

		if(!$movie){
			return $this->respondNotFound();
		}

		$movie = $movieRepository->transform($movie);

		return $this->respond($movie);
	}

	/**
     * @Route("/movies")
	 * @Method("POST")
     */
	public function create(Request $request, MovieRepository $movieRepository, EntityManagerInterface $em)
	{
		$request = $this->transformJsonBody($request);

		if(!$request){
			return $this->respondValidationError('Please provide a valid request!');
		}

		if(!$request->get('title')){
			return $this->respondValidationError('Please provide a title!');
		}

		$movie = new Movie;
		$movie->setTitle($request->get('title'));
		$movie->setCount(0);
		$em->persist($movie);
		$em->flush();
		
		return $this->respondCreated($movieRepository->transform($movie));
	}

	public function increaseCount($id, MovieRepository $movieRepository, EntityManagerInterface $em)
	{
		$movie = $movieRepository->find($id);

		if(!$movie){
			return $this->respondNotFound();
		}

		$movie->setCount($movie->getCount() + 1);
		$em->persist($movie);
		$em->flush();

		return $this->respond([
			'count' => $movie->getCount()
		]);
	}

}