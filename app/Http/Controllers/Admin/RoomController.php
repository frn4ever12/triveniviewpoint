<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RoomDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index(RoomDataTable $dataTable)
    {
        return $dataTable->render('admin.room.index');
    }

    public function create()
    {
        return view('admin.room.create');
    }

    public function store(RoomRequest $request)
    {
        DB::beginTransaction();
        try {
            Room::create($request->validated());
            DB::commit();

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Room created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to create room. Please try again.');
        }
    }

    public function show(Room $room)
    {
        return view('admin.room.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('admin.room.edit', compact('room'));
    }

    public function update(RoomRequest $request, Room $room)
    {
        DB::beginTransaction();
        try {
            $room->update($request->validated());
            DB::commit();

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to update room. Please try again.');
        }
    }

    public function destroy(Room $room)
    {
        DB::beginTransaction();
        try {
            $room->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Room deleted successfully.']);
            }

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Room deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Failed to delete room.'], 500);
            }

            return back()->with('error', 'Failed to delete room. Please try again.');
        }
    }

}

