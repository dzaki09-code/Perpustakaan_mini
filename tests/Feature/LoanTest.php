<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_to_borrow_a_book()
    {
        $user = User::create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'Author Name',
            'stock' => 5,
        ]);

        $response = $this->actingAs($user)
            ->post(route('loans.borrow', $book));

        $response->assertRedirect();
        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_a_loan_request()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        $user = User::create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'Author Name',
            'stock' => 5,
        ]);

        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('loans.approve', $loan));

        $response->assertRedirect();
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'approved',
        ]);
        
        $book->refresh();
        $this->assertEquals(4, $book->stock); // decremented
    }

    public function test_admin_can_reject_a_loan_request()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        $user = User::create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'Author Name',
            'stock' => 5,
        ]);

        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('loans.reject', $loan));

        $response->assertRedirect();
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'rejected',
        ]);
    }

    public function test_admin_can_process_returned_book()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        $user = User::create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'Author Name',
            'stock' => 5,
        ]);

        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('loans.return', $loan));

        $response->assertRedirect();
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'returned',
        ]);

        $book->refresh();
        $this->assertEquals(6, $book->stock); // incremented back
    }
}
