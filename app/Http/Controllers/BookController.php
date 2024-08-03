<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    function index(): JsonResponse
    {
        $books = Book::with('category')->get();
        if($books->isEmpty()){
            return $this->notFoundResponse($books, "Books not found");
        }
        return $this->successResponse($books, "Books retrieved successfully");
    }

    function store(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'category_id' => 'required|integer',
        ]);


        $book = Book::create($validated);

        return response()->json($book, 201);
    }

    function show(int $id): JsonResponse
    {
        $book = Book::findOrFail($id);
        return response()->json($book);
    }

    function update(Request $request, int $id): JsonResponse
    {
        $book = Book::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'title' => 'sometimes|required|max:20',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $book->update($validated->validated());
        return $this->successResponse($book, "Book updated successfully");
    }

    function destroy(int $id): JsonResponse
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json([
            "message" => "Book successfully deleted"
        ], 200);
    }
}
