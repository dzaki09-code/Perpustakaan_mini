@extends('panel_control.components.main')

@section('title', ($mode === 'edit' ? __('edit') : __('addBook')) . ' | ' . __('dashboard'))

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ $mode === 'edit' ? __('editBook') : __('addBook') }}</h4>
            <p class="text-muted mb-0">{{ __('bookFormSubtitle') }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="bookForm" method="POST"
                action="{{ $mode === 'edit' ? route('books.update', $book) : route('books.store') }}">
                @csrf
                @if ($mode === 'edit')
                    @method('PUT')
                @endif
                <input type="hidden" id="read_url" name="read_url" value="{{ old('read_url', $book->read_url) }}" />

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label">{{ __('title') }}</label>
                        <input type="text" id="title" name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $book->title) }}" required />
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="author" class="form-label">{{ __('author') }}</label>
                        <input type="text" id="author" name="author"
                            class="form-control @error('author') is-invalid @enderror"
                            value="{{ old('author', $book->author) }}" required />
                        @error('author')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="publisher" class="form-label">{{ __('publisher') }}</label>
                        <input type="text" id="publisher" name="publisher"
                            class="form-control @error('publisher') is-invalid @enderror"
                            value="{{ old('publisher', $book->publisher) }}" />
                        @error('publisher')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label for="publication_year" class="form-label">{{ __('year') }}</label>
                        <input type="number" id="publication_year" name="publication_year"
                            class="form-control @error('publication_year') is-invalid @enderror"
                            value="{{ old('publication_year', $book->publication_year) }}" min="1500"
                            max="{{ date('Y') }}" />
                        @error('publication_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="category" class="form-label">{{ __('category') }}</label>
                        <input type="text" id="category" name="category"
                            class="form-control @error('category') is-invalid @enderror"
                            value="{{ old('category', $book->category) }}" />
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="isbn" class="form-label">{{ __('isbn') }}</label>
                        <input type="text" id="isbn" name="isbn"
                            class="form-control @error('isbn') is-invalid @enderror"
                            value="{{ old('isbn', $book->isbn) }}" />
                        @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <button type="button" id="openLibraryLookup" class="btn btn-outline-primary btn-sm w-100 mt-2">
                            <i class="bx bx-search-alt-2 me-1"></i>{{ __('searchOpenLibrary') }}
                        </button>
                        <div class="form-text mt-1">{{ __('isbnHint') }}</div>
                    </div>

                    <div class="col-md-2">
                        <label for="stock" class="form-label">{{ __('stock') }}</label>
                        <input type="number" id="stock" name="stock"
                            class="form-control @error('stock') is-invalid @enderror"
                            value="{{ old('stock', $book->stock ?? 0) }}" min="0" required />
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">{{ __('description') }}</label>
                        <textarea id="description" name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $book->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <div>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">{{ __('back') }}</a>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">{{ __('cancel') }}</a>
                        <button type="submit"
                            class="btn btn-primary">{{ $mode === 'edit' ? __('update') : __('save') }}</button>
                    </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="openLibraryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">{{ __('openLibraryResults') }}</h5>
                        <small class="text-muted">{{ __('openLibraryResultsHint') }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="openLibraryStatus" class="text-muted">{{ __('noSearchYet') }}</div>
                    <div id="openLibraryResults" class="list-group mt-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lookupButton = document.getElementById('openLibraryLookup');
            const modalElement = document.getElementById('openLibraryModal');
            const modal = modalElement ? new bootstrap.Modal(modalElement) : null;
            const resultsContainer = document.getElementById('openLibraryResults');
            const statusElement = document.getElementById('openLibraryStatus');
            const titleInput = document.getElementById('title');
            const authorInput = document.getElementById('author');
            const publisherInput = document.getElementById('publisher');
            const publicationYearInput = document.getElementById('publication_year');
            const categoryInput = document.getElementById('category');
            const isbnInput = document.getElementById('isbn');
            const descriptionInput = document.getElementById('description');
            const readUrlInput = document.getElementById('read_url');

            if (!lookupButton) {
                return;
            }

            const endpoint = @json(route('books.open_library.search'));

            const setButtonState = function(isLoading) {
                lookupButton.disabled = isLoading;
                lookupButton.textContent = isLoading ? '{{ __('searching') }}' : '{{ __('searchOpenLibrary') }}';
            };

            const renderResults = function(results) {
                resultsContainer.innerHTML = '';

                if (!results.length) {
                    statusElement.textContent = '{{ __('noResults') }}';
                    return;
                }

                statusElement.innerHTML = results.length + ' {{ __('resultsFound', ['count' => '']) }}'.replace(':count', results.length);

                results.forEach(function(book, index) {
                    const item = document.createElement('div');
                    item.className = 'list-group-item';

                    const row = document.createElement('div');
                    row.className = 'd-flex justify-content-between align-items-start gap-3';

                    const content = document.createElement('div');
                    content.className = 'flex-grow-1';

                    const title = document.createElement('div');
                    title.className = 'fw-semibold';
                    title.textContent = `${index + 1}. ${book.title || '{{ __('noTitle') }}'}`;

                    const meta = document.createElement('div');
                    meta.className = 'text-muted small';
                    meta.textContent = [book.author, book.publisher, book.publication_year].filter(
                        Boolean).join(' • ') || '{{ __('noAdditionalData') }}';

                    const details = document.createElement('div');
                    details.className = 'small mt-2';
                    details.textContent =
                        `ISBN: ${book.isbn || '-'} | {{ __('category') }}: ${book.category || '-'}`;

                    const description = document.createElement('div');
                    description.className = 'small text-muted mt-2';
                    description.textContent = book.description || '{{ __('noDescription') }}';

                    const actionWrap = document.createElement('div');
                    actionWrap.className = 'flex-shrink-0';

                    const chooseButton = document.createElement('button');
                    chooseButton.type = 'button';
                    chooseButton.className = 'btn btn-primary btn-sm';
                    chooseButton.textContent = '{{ __('select') }}';
                    chooseButton.addEventListener('click', function() {
                        if (book.title) titleInput.value = book.title;
                        if (book.author) authorInput.value = book.author;
                        if (book.publisher) publisherInput.value = book.publisher;
                        if (book.publication_year) publicationYearInput.value = book
                            .publication_year;
                        if (book.category) categoryInput.value = book.category;
                        if (book.isbn) isbnInput.value = book.isbn;
                        if (book.description) descriptionInput.value = book.description;
                        if (readUrlInput) readUrlInput.value = book.read_url || '';

                        modal.hide();
                    });

                    content.appendChild(title);
                    content.appendChild(meta);
                    content.appendChild(details);
                    content.appendChild(description);
                    actionWrap.appendChild(chooseButton);
                    row.appendChild(content);
                    row.appendChild(actionWrap);
                    item.appendChild(row);

                    resultsContainer.appendChild(item);
                });
            };

            lookupButton.addEventListener('click', async function() {
                const isbn = isbnInput.value.trim();
                const title = titleInput.value.trim();

                if (!isbn && !title) {
                    alert('{{ __('fillIsbnOrTitle') }}');
                    return;
                }

                statusElement.textContent = '{{ __('loadingOpenLibrary') }}';
                resultsContainer.innerHTML = '';
                modal.show();
                setButtonState(true);

                try {
                    const params = new URLSearchParams();

                    if (isbn) {
                        params.set('isbn', isbn);
                    }

                    if (title) {
                        params.set('title', title);
                    }

                    const response = await fetch(`${endpoint}?${params.toString()}`, {
                        headers: {
                            Accept: 'application/json',
                        },
                    });

                    const payload = await response.json();

                    if (!response.ok) {
                        throw new Error(payload.message ||
                            'Gagal mengambil data dari Project Gutenberg.');
                    }

                    renderResults(payload.results || []);
                } catch (error) {
                    modal.hide();
                    alert(error.message);
                } finally {
                    setButtonState(false);
                }
            });
        });
    </script>
@endpush
