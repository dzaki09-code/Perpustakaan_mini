<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class LoanService
{
    public function createLoan($bookId)
    {
        $book = Book::findOrFail($bookId);

        if ($book->stock <= 0) {
            return false;
        }

        return Loan::create([
            'user_id' => Auth::id(),
            'book_id' => $bookId,
            'borrow_date' => now(),
            'status' => 'pending'
        ]);
    }

    public function approveLoan(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return;
        }

        $loan->book->decrement('stock');

        $loan->update([
            'status' => 'approved'
        ]);
    }

    public function rejectLoan(Loan $loan)
    {
        $loan->update([
            'status' => 'rejected'
        ]);
    }

    public function returnBook(Loan $loan)
    {
        if ($loan->status !== 'approved') {
            return;
        }

        $loan->book->increment('stock');

        $loan->update([
            'status' => 'returned',
            'return_date' => now()
        ]);
    }
}
