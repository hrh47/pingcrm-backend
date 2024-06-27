<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = Auth::user()->account->users()
            ->orderByName()
            ->filter($request->only('search', 'role', 'trashed'))
            ->get();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request['data'] = json_decode($request->get('data'), true);
        $request->validate([
            'data.first_name' => ['required', 'max:50'],
            'data.last_name' => ['required', 'max:50'],
            'data.email' => ['required', 'max:50', 'email', Rule::unique('users', 'email')],
            'data.password' => ['nullable'],
            'data.owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        Auth::user()->account->users()->create([
            'first_name' => $request->input('data.first_name'),
            'last_name' => $request->input('data.last_name'),
            'email' => $request->input('data.email'),
            'password' => $request->input('data.password'),
            'owner' => $request->input('data.owner'),
            'photo_path' => $request->file('photo') ? $request->file('photo')->store('users') : null,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request['data'] = json_decode($request->get('data'), true);
        $request->validate([
            'data.first_name' => ['required', 'max:50'],
            'data.last_name' => ['required', 'max:50'],
            'data.email' => ['required', 'max:50', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'data.password' => ['nullable'],
            'data.owner' => ['required', 'boolean'],
            'data.photo' => ['nullable', 'image'],
        ]);

        $user->update($request->only('data.first_name', 'data.last_name', 'data.email', 'data.owner'));

        if ($request->file('photo')) {
            $user->update(['photo_path' => $request->file('photo')->store('users')]);
        }

        if ($request->get('password')) {
            $user->update(['password' => $request->input('data.password')]);
        }

        return ['success' => true];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
    }

    public function restore(User $user)
    {
        $user->restore();
    }
}
