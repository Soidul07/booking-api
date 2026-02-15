<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrganizationController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('viewAny', Organization::class);
        
        $query = Organization::query();
        
        if (!$request->user()->hasRole('super_admin')) {
            $query->where('id', $request->user()->organization_id);
        }
        
        return OrganizationResource::collection($query->with('teams')->get());
    }

    public function store(OrganizationRequest $request)
    {
        $this->authorize('create', Organization::class);
        
        $organization = Organization::create($request->validated());
        return new OrganizationResource($organization);
    }

    public function show(Request $request, Organization $organization)
    {
        $this->authorize('view', $organization);
        return new OrganizationResource($organization->load('teams.members'));
    }

    public function update(OrganizationRequest $request, Organization $organization)
    {
        $this->authorize('update', $organization);
        
        $organization->update($request->validated());
        return new OrganizationResource($organization);
    }

    public function destroy(Request $request, Organization $organization)
    {
        $this->authorize('delete', $organization);
        
        $organization->delete();
        return response()->json(['message' => 'Organization deleted successfully']);
    }
}
