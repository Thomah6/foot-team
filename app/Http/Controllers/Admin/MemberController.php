<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Display a listing of members with filtering and search
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('pseudo', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('is_active', $status === 'active');
        }

        // Paginate results
        $perPage = $request->input('perPage', 10);

        $members = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        // Compter les utilisateurs inactifs pour la notification
        $inactiveUsersCount = User::where('is_active', false)->count();

        return Inertia::render('Admin/Members/Index', [
            'members' => $members,
            'filters' => [
                'search' => $request->input('search', ''),
                'role' => $request->input('role', ''),
                'status' => $request->input('status', ''),
                'perPage' => $perPage,
            ],
            'inactiveUsersCount' => $inactiveUsersCount,
        ]);
    }

    /**
     * Show the form for creating a new member
     */
    public function create()
    {
        return Inertia::render('Admin/Members/Create');
    }

    /**
     * Store a newly created member
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pseudo' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,bureau,simple',
            'position' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        User::create($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre cree avec succes');
    }

    /**
     * Show the form for editing a member
     */
    public function edit(User $member)
    {
        return Inertia::render('Admin/Members/Edit', [
            'member' => $member,
        ]);
    }

    /**
     * Update the specified member
     */
    public function update(Request $request, User $member)
    {
        $validated = $request->validate([
            'pseudo' => 'required|string|max:255|unique:users,pseudo,' . $member->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,bureau,simple',
            'position' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Membre modifie avec succes');
    }

    /**
     * Delete a member
     */
    public function destroy(User $member)
    {
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre supprime avec succes');
    }

    /**
     * Toggle member activation status
     */
    public function toggleStatus(User $member)
    {
        $member->update(['is_active' => !$member->is_active]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Statut du membre modifie');
    }

    /**
     * Update member role
     */
    public function updateRole(Request $request, User $member)
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,bureau,simple',
        ]);

        $member->update($validated);

        return back()->with('success', 'Role modifie avec succes');
    }
}
