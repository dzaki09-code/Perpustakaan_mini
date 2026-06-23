<?php

namespace App\Http\Controllers\PanelControl;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function __construct(
        private LoanService $loanService
    ) {}

    /**
     * Menampilkan daftar peminjaman.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        $query = Loan::with(['book', 'user'])
            ->whereIn('status', ['pending', 'approved'])
            ->latest();

        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        if ($user->isAdmin() && $search = $request->query('q')) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('book', fn ($book) => $book->where('title', 'like', "%{$search}%"))
                      ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%"));
            });
        }

        $loans = $query->paginate(10);

        return view('panel_control.loans.index', compact('loans'));
    }

    /**
     * Pengunjung mengajukan peminjaman buku.
     */
    public function borrow(Book $book): RedirectResponse
    {
        $result = $this->loanService->createLoan($book->id);

        if (!$result) {
            return redirect()
                ->back()
                ->with('error', 'Stok buku tidak tersedia.');
        }

        return redirect()
            ->back()
            ->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    /**
     * Admin menyetujui peminjaman.
     */
    public function approve(Loan $loan): RedirectResponse
    {
        $this->loanService->approveLoan($loan);

        return redirect()
            ->back()
            ->with('success', 'Peminjaman berhasil disetujui.');
    }

    /**
     * Admin menolak peminjaman.
     */
    public function reject(Loan $loan): RedirectResponse
    {
        $this->loanService->rejectLoan($loan);

        return redirect()
            ->back()
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Admin mengembalikan buku.
     */
    public function returnBook(Loan $loan): RedirectResponse
    {
        $this->loanService->returnBook($loan);

        return redirect()
            ->back()
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Menampilkan detail peminjaman.
     */
    public function history(Request $request): View
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        $query = Loan::with(['book', 'user'])
            ->whereIn('status', ['returned', 'rejected'])
            ->latest();

        if (! $user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        if ($user->isAdmin() && $search = $request->query('q')) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('book', fn ($book) => $book->where('title', 'like', "%{$search}%"))
                      ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$search}%"));
            });
        }

        $loans = $query->paginate(10);

        return view('panel_control.loans.history', compact('loans'));
    }

    public function show(Loan $loan): View
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        if (!$user->isAdmin() && $loan->user_id !== $user->id) {
            abort(403);
        }

        $loan->load(['user', 'book']);

        return view('panel_control.loans.show', compact('loan'));
    }
}
