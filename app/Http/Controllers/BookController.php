<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function store(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'category_id' => 'required|integer',
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validated->fails()) {
            return $this->invalidFieldResponse($validated->errors());
        }

        if (!$request->hasFile('cover')) {
            return $this->errorResponse(null, "Image upload failed", 400);
        }

        // take request file
        $image = $request->file('cover');

        // randomize image name
        $imageName = Str::random(32) . '.' . $image->getClientOriginalExtension();

        // store file in storage create folder books
        $imagePath = $image->storeAs('books', $imageName, 'public');

        // convert name with storage path
        $imageUrl = Storage::url($imagePath);

        $book = Book::create([
            'title' => $request->title,
            'category_id' => (int) $request->category_id,
            'cover' => $imageName,
        ]);

        $book->cover_url = $imageUrl; // Add the cover URL to the response

        return $this->successResponse($book, "Book created successfully", 201);
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
