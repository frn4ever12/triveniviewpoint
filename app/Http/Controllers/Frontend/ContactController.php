<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        DB::beginTransaction();

        try {

            $contact = Contact::create($request->validated());


            DB::commit();
            return back()->with('success', 'Thank you for your message. We will contact you soon.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'Failed to send message. Please try again.');
        }
    }
}
