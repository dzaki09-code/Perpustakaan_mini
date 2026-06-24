<?php

namespace App\Http\Controllers\PanelControl;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        $isbn = isset($data['isbn']) ? strtoupper(preg_replace('/[^0-9Xx]/', '', $data['isbn'])) : null;
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

        $results = $this->searchOpenLibrary($isbn, $title);

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
            'cover_url' => ['nullable', 'url', 'max:2048'],
        ]);
    }

    private function searchOpenLibrary(?string $isbn, ?string $title): array
    {
        $fetchBooks = function () use ($isbn, $title) {
            try {
                $baseUrl = rtrim(config('services.open_library.base_url') ?: 'https://openlibrary.org', '/');
                $http = Http::timeout((int) config('services.open_library.timeout', 10));

                if (! config('services.open_library.verify_ssl', true)) {
                    $http = $http->withoutVerifying();
                }

                $response = $http->get($baseUrl . '/search.json', array_filter([
                'isbn' => $isbn,
                'title' => $isbn ? null : $title,
                'fields' => implode(',', [
                    'key',
                    'title',
                    'author_name',
                    'first_publish_year',
                    'publisher',
                    'publish_year',
                    'isbn',
                    'subject',
                    'cover_i',
                ]),
                'limit' => 5,
                ], fn ($value) => $value !== null && $value !== ''));
            } catch (\Throwable $th) {
                return [];
            }

            if (! $response->successful()) {
                return [];
            }

            $books = $response->json('docs') ?? [];

            return collect($books)
                ->map(fn (array $book) => $this->formatOpenLibraryBook($book, $isbn))
                ->filter(fn (array $book) => ! empty($book['title']))
                ->values()
                ->all();
        };

        $cacheTtl = (int) config('services.open_library.cache_ttl', 0);

        if ($cacheTtl <= 0) {
            return $fetchBooks();
        }

        try {
            $cacheKey = 'open_library:search:' . md5(json_encode([$isbn, $title]));

            return Cache::store(config('services.open_library.cache_store'))->remember($cacheKey, $cacheTtl, $fetchBooks);
        } catch (\Throwable $th) {
            return $fetchBooks();
        }
    }

    private function formatOpenLibraryBook(array $book, ?string $searchedIsbn = null): array
    {
        $subjects = data_get($book, 'subject') ?? [];
        $isbnList = data_get($book, 'isbn') ?? [];
        $publisher = data_get($book, 'publisher.0');
        $category = $subjects[0] ?? 'Umum';
        $description = 'Data buku dari Open Library.';
        $coverId = data_get($book, 'cover_i');

        if (! empty($subjects)) {
            $description .= ' Subjek: ' . implode(', ', array_slice($subjects, 0, 5)) . '.';
        }

        return [
            'title' => data_get($book, 'title'),
            'author' => implode(', ', data_get($book, 'author_name') ?? []) ?: 'Unknown Author',
            'publisher' => $publisher ?: 'Open Library',
            'publication_year' => data_get($book, 'first_publish_year'),
            'category' => substr($category, 0, 50),
            'isbn' => $this->preferredIsbn($isbnList, $searchedIsbn),
            'description' => $description,
            'cover_url' => $coverId ? "https://covers.openlibrary.org/b/id/{$coverId}-L.jpg" : null,
        ];
    }

    private function preferredIsbn(array $isbnList, ?string $searchedIsbn = null): ?string
    {
        $normalizedIsbnList = collect($isbnList)
            ->map(fn (string $isbn) => strtoupper(preg_replace('/[^0-9Xx]/', '', $isbn)))
            ->filter()
            ->values();

        if ($searchedIsbn && $normalizedIsbnList->contains($searchedIsbn)) {
            return $searchedIsbn;
        }

        return $normalizedIsbnList
            ->sortByDesc(fn (string $isbn) => strlen($isbn))
            ->first();
    }
}
