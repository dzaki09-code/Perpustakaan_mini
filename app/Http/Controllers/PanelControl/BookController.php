<?php

namespace App\Http\Controllers\PanelControl;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function show(Book $book)
    {
        return view('panel_control.books.show', compact('book'));
    }

    public function index(Request $request)
    {
        $books = Book::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', '%' . $search . '%')
                        ->orWhere('author', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%')
                        ->orWhere('isbn', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('panel_control.books.index', compact('books'));
    }

    public function create()
    {
        return view('panel_control.books.form', [
            'book' => new Book(),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateBook($request);

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        return view('panel_control.books.form', [
            'book' => $book,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $validated = $this->validateBook($request, $book->id);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Data buku berhasil dihapus.');
    }

    public function openLibrarySearch(Request $request)
    {
        $data = $request->validate([
            'isbn' => ['nullable', 'string', 'max:32'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $isbn = isset($data['isbn']) ? preg_replace('/[^0-9Xx]/', '', $data['isbn']) : null;
        $title = trim($data['title'] ?? '');

        if ($isbn === '' || $isbn === null) {
            $isbn = null;
        }

        if ($title === '') {
            $title = null;
        }

        if (! $isbn && ! $title) {
            return response()->json([
                'message' => 'Isi ISBN atau judul buku terlebih dahulu.',
            ], 422);
        }

        $results = $isbn
            ? $this->searchOpenLibraryByIsbn($isbn)
            : $this->searchOpenLibraryByTitle($title);

        if (empty($results)) {
            return response()->json([
                'message' => 'Data buku tidak ditemukan di Open Library.',
            ], 404);
        }

        return response()->json([
            'results' => $results,
        ]);
    }

    private function validateBook(Request $request, ?int $bookId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'publication_year' => ['nullable', 'integer', 'min:1500', 'max:' . date('Y')],
            'category' => ['nullable', 'string', 'max:255'],
            'isbn' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('books', 'isbn')->ignore($bookId),
            ],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'read_url' => ['nullable', 'string', 'max:2048'],
        ]);
    }

    private function searchOpenLibraryByIsbn(string $isbn): array
    {
        try {
            $response = Http::withoutVerifying()->timeout(10)->get('https://openlibrary.org/api/books', [
                'bibkeys' => 'ISBN:' . $isbn,
                'format' => 'json',
                'jscmd' => 'data',
            ]);
        } catch (\Throwable $th) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $book = $response->json('ISBN:' . $isbn);

        return $book ? [$this->formatOpenLibraryBook($book, $isbn)] : [];
    }

    private function searchOpenLibraryByTitle(string $title): array
    {
        try {
            $response = Http::withoutVerifying()->timeout(10)->get('https://openlibrary.org/search.json', [
                'title' => $title,
                'limit' => 5,
            ]);
        } catch (\Throwable $th) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $books = $response->json('docs') ?? [];

        return collect($books)
            ->take(5)
            ->map(fn (array $book) => $this->formatOpenLibrarySearchResult($book))
            ->filter(fn (array $book) => filled($book['title'] ?? null))
            ->values()
            ->all();
    }

    private function formatOpenLibraryBook(array $book, ?string $fallbackIsbn = null): array
    {
        $isbn = data_get($book, 'identifiers.isbn_13.0')
            ?? data_get($book, 'identifiers.isbn_10.0')
            ?? $fallbackIsbn;

        return [
            'title' => data_get($book, 'title'),
            'author' => data_get($book, 'authors.0.name'),
            'publisher' => data_get($book, 'publishers.0.name'),
            'publication_year' => $this->extractYear(data_get($book, 'publish_date')),
            'category' => data_get($book, 'subjects.0.name'),
            'isbn' => $isbn,
            'description' => data_get($book, 'notes') ?? data_get($book, 'subtitle'),
            'read_url' => data_get($book, 'url'),
        ];
    }

    private function formatOpenLibrarySearchResult(array $book): array
    {
        $isbn = data_get($book, 'isbn.0');

        return [
            'title' => data_get($book, 'title'),
            'author' => data_get($book, 'author_name.0'),
            'publisher' => data_get($book, 'publisher.0'),
            'publication_year' => data_get($book, 'first_publish_year'),
            'category' => data_get($book, 'subject.0'),
            'isbn' => $isbn,
            'description' => data_get($book, 'subtitle'),
            'read_url' => data_get($book, 'key') ? 'https://openlibrary.org' . data_get($book, 'key') : null,
        ];
    }

    private function extractYear(?string $value): ?int
    {
        if (! $value) {
            return null;
        }

        if (preg_match('/(\d{4})/', $value, $matches) !== 1) {
            return null;
        }

        return (int) $matches[1];
    }
}