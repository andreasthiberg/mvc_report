<?php

namespace App\Controller;

use App\Entity\Books;
use App\Repository\BooksRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BooksController extends AbstractController
{
    /**
     * Route for library home page
     *
     * @Route("/books", name="books-home")
     */
    public function booksHome(): Response
    {
        return $this->render('books/books-home.html.twig');
    }

    /**
     * @Route("/books/show", name="books-show")
     */
    public function showAllBooks(
        BooksRepository $booksRepository
    ): Response {
        $books = $booksRepository
            ->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('books/books-show.html.twig', $data);
    }

    /**
     * @Route("/books/create", name="books-create", methods={"GET"})
     */
    public function createBook(): Response
    {
        return $this->render('books/books-create.html.twig');
    }

    /**
     * @Route("/books/create", name="books-create-handler", methods={"POST"})
     */
    public function createBookHandler(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();

        // Create new book from post request
        $book = new Books();

        $book->setTitle($request->request->get('btitle'));
        $book->setAuthor($request->request->get('bauthor'));
        $book->setISBN($request->request->get('bisbn'));
        $book->setImg($request->request->get('bimg'));

        $entityManager->persist($book);

        $entityManager->flush();

        return $this->redirectToRoute('books-home');
    }

    /**
     * @Route("/books/show/{id}", name="books-show-single")
     */
    public function showBookById(
        BooksRepository $booksRepository,
        int $id
    ): Response {
        $book = $booksRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('books/books-show-single.html.twig', $data);
    }

    /**
     * @Route("/books/update/{id}", name="books-update", methods={"GET"})
     */
    public function updateBook(
        BooksRepository $booksRepository,
        int $id
    ): Response {
        $book = $booksRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('books/books-update.html.twig', $data);
    }

    /**
     * @Route("/books/update/{id}", name="books-update-handler", methods={"POST"})
     */
    public function updateBookHandler(
        BooksRepository $booksRepository,
        ManagerRegistry $doctrine,
        Request $request,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = $booksRepository
            ->find($id);

        $action = $request->request->get('action');

        if ($action == "remove") {
            if (!$book) {
                throw $this->createNotFoundException(
                    'No product found for id ' . $id
                );
            }

            $entityManager->remove($book);
            $entityManager->flush();

            return $this->redirectToRoute('books-show');
        }

        $book->setTitle($request->request->get('btitle'));
        $book->setAuthor($request->request->get('bauthor'));
        $book->setISBN($request->request->get('bisbn'));
        $book->setImg($request->request->get('bimg'));

        $entityManager->persist($book);

        $entityManager->flush();

        return $this->redirectToRoute('books-show-single', ["id" => $id]);
    }
}
