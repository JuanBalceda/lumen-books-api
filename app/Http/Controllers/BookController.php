<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    use ApiResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Return book list
     * @return JsonResponse
     */
    public function index()
    {
        $books = Book::all();

        return $this->successResponse($books);
    }

    /**
     * Create an instance of Book
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|min:1',
            'author_id' => 'required|min:1'
        ];

        $this->validate($request, $rules);

        $book = Book::create($request->all());

        return $this->successResponse($book, Response::HTTP_CREATED);
    }

    /**
     * Return an instance of Book
     * @param int $idBook
     * @return JsonResponse
     */
    public function show($idBook)
    {

        $book = Book::findOrFail($idBook);

        return $this->successResponse($book);
    }

    /**
     * Update an specific book
     * @param Request $request
     * @param int $idBook
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $idBook)
    {
        $rules = [
            'title' => 'max:255',
            'description' => 'max:255',
            'price' => 'min:1',
            'author_id' => 'min:1'
        ];

        $this->validate($request, $rules);

        $book = Book::findOrFail($idBook);
        $book->fill($request->all());

        if ($book->isClean()) {
            return $this->errorResponse('at least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book->save();

        return $this->successResponse($book);
    }

    /**
     * Delete an instance of Book
     * @param int $idBook
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($idBook)
    {
        $book = Book::findOrFail($idBook);

        $book->delete();

        return $this->successResponse($book);
    }
}
