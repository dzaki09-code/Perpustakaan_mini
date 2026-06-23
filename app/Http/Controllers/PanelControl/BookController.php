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

        $isbn = isset($data['isbn']) ? preg_replace('/[^0-9]/', '', $data['isbn']) : null;
        $title = trim($data['title'] ?? '');

        if ($isbn === '' || $isbn === null) {
            $isbn = null;
        }

        if ($title === '') {
            $title = null;
        }

        if (! $isbn && ! $title) {
            return response()->json([
                'message' => 'Isi ID Gutenberg atau judul buku terlebih dahulu.',
            ], 422);
        }

        $results = $isbn
            ? $this->searchGutenbergById($isbn)
            : $this->searchGutenbergByQuery($title);

        if (empty($results)) {
            return response()->json([
                'message' => 'Data buku tidak ditemukan di Project Gutenberg.',
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

    private function searchGutenbergById(string $id): array
    {
        try {
            $response = Http::withoutVerifying()->timeout(10)->get('https://gutendex.com/books/', [
                'ids' => $id,
            ]);
        } catch (\Throwable $th) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $books = $response->json('results') ?? [];

        return collect($books)
            ->map(fn (array $book) => $this->formatGutenbergBook($book))
            ->all();
    }

    private function searchGutenbergByQuery(string $query): array
    {
        try {
            $response = Http::withoutVerifying()->timeout(10)->get('https://gutendex.com/books/', [
                'search' => $query,
            ]);
        } catch (\Throwable $th) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $books = $response->json('results') ?? [];

        return collect($books)
            ->take(5)
            ->map(fn (array $book) => $this->formatGutenbergBook($book))
            ->all();
    }

    private function formatGutenbergBook(array $book): array
    {
        $id = data_get($book, 'id');
        
        $authors = data_get($book, 'authors') ?? [];
        $authorName = 'Unknown Author';
        if (!empty($authors)) {
            $rawName = data_get($authors[0], 'name', 'Unknown Author');
            if (str_contains($rawName, ',')) {
                $parts = explode(',', $rawName);
                $authorName = trim($parts[1]) . ' ' . trim($parts[0]);
            } else {
                $authorName = $rawName;
            }
        }

        $formats = data_get($book, 'formats') ?? [];
        $readUrl = null;
        foreach ($formats as $mime => $url) {
            if (str_contains($mime, 'text/html') || str_contains($mime, 'html')) {
                $readUrl = $url;
                break;
            }
        }
        if (!$readUrl) {
            foreach ($formats as $mime => $url) {
                if (str_contains($mime, 'text/plain') || str_contains($mime, 'text')) {
                    $readUrl = $url;
                    break;
                }
            }
        }

        $category = data_get($book, 'bookshelves.0') ?? data_get($book, 'subjects.0') ?? 'Sastra';
        if (str_contains($category, '--')) {
            $category = trim(explode('--', $category)[0]);
        }

        $subjects = data_get($book, 'subjects') ?? [];
        $languages = data_get($book, 'languages') ?? [];
        $desc = 'Karya sastra klasik bebas hak cipta dari Project Gutenberg.';
        if (!empty($subjects)) {
            $desc .= ' Subjek: ' . implode(', ', $subjects) . '.';
        }
        if (!empty($languages)) {
            $desc .= ' Bahasa: ' . strtoupper(implode(', ', $languages)) . '.';
        }

        return [
            'title' => data_get($book, 'title'),
            'author' => $authorName,
            'publisher' => 'Project Gutenberg',
            'publication_year' => null,
            'category' => substr($category, 0, 50),
            'isbn' => 'GUTENBERG-' . $id,
            'description' => $desc,
            'read_url' => $readUrl,
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