<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    function index(): JsonResponse
    {
        $books = Book::all();
        if($books->isEmpty()){
            return response()->json(["message" => "No books found"], 404);
        }
        return response()->json($books);
    }

    function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
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
        $validated = $request->validate([
            'title' => 'required|max:255',
        ]);

        $book = Book::findOrFail($id);
        $book->update($validated);
        return response()->json($book);
    }
}
