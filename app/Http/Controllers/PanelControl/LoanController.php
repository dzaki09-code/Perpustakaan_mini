<?php

namespace App\Http\Controllers\PanelControl;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
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
    public function index(): View
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        if ($user->isAdmin()) {
            $loans = Loan::with([
                'user',
                'book'
            ])->latest()->get();
        } else {
            $loans = Loan::with('book')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

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
}
