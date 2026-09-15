<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Traits\FileUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use FileUploadTrait;
    
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the admin profile.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Get validated data
        $validatedData = $request->validated();
        
        // Remove profile_image from validated data as we'll handle it separately
        unset($validatedData['profile_image']);
        
        // Fill user with validated data (excluding profile_image)
        $user->fill($validatedData);

        // If email changed, reset verification date
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save the user first
        $user->save();

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Clear existing profile image first
            $user->clearMediaCollection('profile_image');
            
            // Upload new profile image
            $this->uploadToMediaLibrary($user, $request->file('profile_image'), 'profile_image');
        }

        return Redirect::route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * (Optional) Delete the admin's own account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}