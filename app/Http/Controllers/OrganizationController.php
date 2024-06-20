<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $organizations = Auth::user()
            ->account
            ->organizations()
            ->orderBy('name')
            ->filter($request->only('search', 'trashed'));

        if (request('page')) {
            $organizations = $organizations->paginate(10);
        } else {
            $organizations = $organizations->select(['id', 'name'])->get();
        }

        return OrganizationResource::collection($organizations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $organization = Auth::user()->account->organizations()->create(
            $request->validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        return OrganizationResource::make($organization->load('contacts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        $organization = $organization->update(
            $request->validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();
    }

    public function restore(Organization $organization)
    {
        $organization->restore();
    }
}
