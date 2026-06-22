<?php

namespace App\Http\Controllers\PanelControl;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('role', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('panel_control.users.index', compact('users'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', [
                User::STATUS_ACTIVE,
                User::STATUS_INACTIVE,
                User::STATUS_BLOCKED,
            ])],
        ]);

        if ($request->user()->is($user)) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak dapat mengubah status akun sendiri.');
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Status pengguna berhasil diperbarui.');
    }
}
