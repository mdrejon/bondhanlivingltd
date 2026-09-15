<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    public function index(): Response
    {
        $rooms = Room::with('roomType')
            ->orderBy('room_number')
            ->get();

        $roomTypes = RoomType::orderBy('sort_order')->get(['id', 'name']);

        return Inertia::render('Admin/Rooms/Index', [
            'rooms'     => $rooms,
            'roomTypes' => $roomTypes,
        ]);
    }

    public function create(): Response
    {
        $roomTypes = RoomType::active()->get(['id', 'name', 'price']);

        return Inertia::render('Admin/Rooms/Create', [
            'roomTypes' => $roomTypes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        Room::create($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function edit(Room $room): Response
    {
        $roomTypes = RoomType::orderBy('sort_order')->get(['id', 'name', 'price']);

        return Inertia::render('Admin/Rooms/Edit', [
            'room'      => $room,
            'roomTypes' => $roomTypes,
        ]);
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $data = $this->validated($request, $room->id);

        if ($request->hasFile('image')) {
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $data['image'] = $request->file('image')->store('rooms', 'public');
        } else {
            unset($data['image']);
        }

        $room->update($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }
        $room->delete();

        return back()->with('success', 'Room deleted.');
    }

    public function toggleStatus(Room $room): RedirectResponse
    {
        $room->update(['is_active' => !$room->is_active]);

        return back()->with('success', 'Status updated.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number'  => 'required|string|unique:rooms,room_number' . ($ignoreId ? ",$ignoreId" : ''),
            'room_name'    => 'nullable|string',
            'floor'        => 'nullable|string',
            'building'     => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpeg,jpg,png,webp',
            'status'       => 'required|in:available,occupied,maintenance,out_of_order',
            'notes'        => 'nullable|string',
            'is_active'    => 'boolean',
        ]);
    }
}
