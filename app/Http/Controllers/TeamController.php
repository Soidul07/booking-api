<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use App\Http\Resources\TeamResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TeamController extends Controller
{
    use AuthorizesRequests;
    public function store(TeamRequest $request, Organization $organization)
    {
        $this->authorize('manageTeams', $organization);
        
        $team = $organization->teams()->create($request->validated());
        return new TeamResource($team);
    }

    public function update(TeamRequest $request, Organization $organization, Team $team)
    {
        $this->authorize('manageTeams', $organization);
        
        if ($team->organization_id !== $organization->id) {
            return response()->json(['message' => 'Team does not belong to organization'], 403);
        }
        
        $team->update($request->validated());
        return new TeamResource($team);
    }

    public function destroy(Request $request, Organization $organization, Team $team)
    {
        $this->authorize('manageTeams', $organization);
        
        if ($team->organization_id !== $organization->id) {
            return response()->json(['message' => 'Team does not belong to organization'], 403);
        }
        
        $team->delete();
        return response()->json(['message' => 'Team deleted successfully']);
    }

    public function addMember(Request $request, Organization $organization, Team $team)
    {
        $this->authorize('manageTeams', $organization);
        
        $request->validate(['user_id' => 'required|exists:users,id']);
        
        $user = User::findOrFail($request->user_id);
        
        if ($user->organization_id !== $organization->id) {
            return response()->json(['message' => 'User does not belong to organization'], 403);
        }
        
        $team->members()->syncWithoutDetaching([$user->id]);
        return new TeamResource($team->load('members'));
    }

    public function removeMember(Request $request, Organization $organization, Team $team, User $user)
    {
        $this->authorize('manageTeams', $organization);
        
        $team->members()->detach($user->id);
        return response()->json(['message' => 'Member removed successfully']);
    }
}
