<?php

namespace App\Http\Controllers\PanelControl;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Core counters
        $bookCount = Book::count();
        $userCount = User::count();
        $totalStock = Book::sum('stock');
        $categoryCount = Book::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->count('category');

        // 2. Lists for tables
        $latestBooks = Book::latest()->take(5)->get();
        $latestUsers = User::latest()->take(5)->get();

        // 3. Category distribution (Top 5 categories + others)
        $categoriesData = Book::whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        // Reformat for the chart
        $chartLabels = [];
        $chartSeries = [];
        foreach ($categoriesData as $item) {
            $chartLabels[] = $item->category;
            $chartSeries[] = (int) $item->count;
        }

        return view('panel_control.dashboard', [
            'bookCount' => $bookCount,
            'userCount' => $userCount,
            'totalStock' => $totalStock,
            'categoryCount' => $categoryCount,
            'latestBooks' => $latestBooks,
            'latestUsers' => $latestUsers,
            'chartLabels' => $chartLabels,
            'chartSeries' => $chartSeries,
        ]);
    }
}

