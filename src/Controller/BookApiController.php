<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book/api")
 */
class BookApiController extends AbstractController
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route("", name="get_all_books", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findAll();

        $data = [];

        foreach ($books as $book) {
            $data[] =  $this->castBookToArray($book);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/booksDrama", name="get_drama_books", methods={"GET"})
     */
    public function getDramaBooks(): JsonResponse
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findBy(['category' => 'Drama']);

        $data = [];

        foreach ($books as $book) {
            $data[] = $this->castBookToArray($book);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/booksBefore2013", name="get_books_before_2013", methods={"GET"})
     */
    public function getBooksBefore2013(): JsonResponse
    {
        $date = Carbon::create(2013,1,1);

        $books = $this->bookRepository
            ->findBeforeDate($date);

        $data = [];

        foreach ($books as $book) {
            $data[] = $this->castBookToArray($book);
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("", name="add_book", methods={"POST"})
     */
    public function add(Request $request) : JsonResponse
    {
        $bookData = json_decode($request->getContent(), true);
        $this->bookRepository->saveBook($bookData);

        return new JsonResponse(['status' => 'Book created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{isbn}", name="get_one_book", methods={"GET"})
     */
    public function getBook($isbn): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['isbn' => $isbn]);

        $data[] = $this->castBookToArray($book);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="delete_book", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        if (!is_null($book)) {
            $this->bookRepository->removeBook($book);

            return new JsonResponse(['status' => 'Book deleted'], Response::HTTP_OK);
        }

        return new JsonResponse(['status' => 'Book not found'], Response::HTTP_OK);
    }

    protected function castBookToArray(Book $book) :array
    {
        return Array(
            'isbn' => $book->getIsbn(),
            'title' => $book->getTitle(),
            'subtitle' => $book->getSubtitle(),
            'author' => $book->getAuthor(),
            'published' => $book->getPublished(),
            'publisher' => $book->getPublisher(),
            'pages' => $book->getPages(),
            'description' => $book->getDescription(),
            'website' => $book->getWebsite(),
            'category' => $book->getCategory(),
            'photUrls' => $book->getPhotoUrls()
        );
    }
}
