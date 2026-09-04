<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ContactDataTable;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(ContactDataTable $dataTable)
    {
        return $dataTable->render('admin.contact.index');
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('admin.contact.show', compact('contact'));
    }

    public function updateStatus(Request $request, Contact $contact)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $contact->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        DB::beginTransaction();
        try {
            $contact->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Contact deleted successfully.'
                ]);
            }
            return redirect()->route('contactes.index')
                ->with('success', 'Contact deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to delete contact.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete contact. Please try again.');
        }
    }
}
