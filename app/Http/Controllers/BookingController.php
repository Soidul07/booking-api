<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $query = Booking::query()->with(['organization', 'team', 'creator', 'assignee']);
        
        $user = $request->user();
        
        if (!$user->hasRole('super_admin') && !$user->organization_id) {
            return response()->json(['message' => 'User must belong to an organization'], 403);
        }
        
        if ($user->hasRole('super_admin')) {
            // Super admin sees all
        } else {
            $query->where('organization_id', $user->organization_id);
        }
        
        return BookingResource::collection($query->get());
    }

    public function store(BookingRequest $request)
    {
        $user = $request->user();
        
        if (!$user->organization_id) {
            return response()->json(['message' => 'User must belong to an organization'], 403);
        }
        
        $booking = Booking::create([
            'organization_id' => $user->organization_id,
            'team_id' => $request->team_id,
            'created_by' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'NEW',
        ]);
        
        return new BookingResource($booking->load(['organization', 'team', 'creator']));
    }

    public function show(Request $request, Booking $booking)
    {
        $this->authorize('view', $booking);
        return new BookingResource($booking->load(['organization', 'team', 'creator', 'assignee']));
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        $this->authorize('update', $booking);
        
        $booking->update($request->only(['title', 'description', 'team_id']));
        return new BookingResource($booking);
    }

    public function destroy(Request $request, Booking $booking)
    {
        $this->authorize('delete', $booking);
        
        $booking->delete();
        return response()->json(['message' => 'Booking deleted successfully']);
    }

    public function assign(Request $request, Booking $booking)
    {
        $this->authorize('assign', $booking);
        
        if ($booking->status !== 'NEW') {
            return response()->json(['message' => 'Can only assign NEW bookings'], 422);
        }
        
        $request->validate(['user_id' => 'required|exists:users,id']);
        
        $booking->update([
            'assigned_to' => $request->user_id,
            'status' => 'ASSIGNED',
            'assigned_at' => now(),
        ]);
        
        return new BookingResource($booking->load('assignee'));
    }

    public function start(Request $request, Booking $booking)
    {
        $this->authorize('start', $booking);
        
        if ($booking->status !== 'ASSIGNED') {
            return response()->json(['message' => 'Can only start ASSIGNED bookings'], 422);
        }
        
        $booking->update([
            'status' => 'IN_PROGRESS',
            'started_at' => now(),
        ]);
        
        return new BookingResource($booking);
    }

    public function complete(Request $request, Booking $booking)
    {
        $this->authorize('complete', $booking);
        
        if ($booking->status !== 'IN_PROGRESS') {
            return response()->json(['message' => 'Can only complete IN_PROGRESS bookings'], 422);
        }
        
        $booking->update([
            'status' => 'COMPLETED',
            'completed_at' => now(),
        ]);
        
        return new BookingResource($booking);
    }

    public function cancel(Request $request, Booking $booking)
    {
        $this->authorize('cancel', $booking);
        
        if (in_array($booking->status, ['COMPLETED', 'CANCELLED'])) {
            return response()->json(['message' => 'Cannot cancel COMPLETED or CANCELLED bookings'], 422);
        }
        
        $booking->update([
            'status' => 'CANCELLED',
            'cancelled_at' => now(),
        ]);
        
        return new BookingResource($booking);
    }
}
