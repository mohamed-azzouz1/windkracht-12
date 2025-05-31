<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminProfile;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the admin profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = AdminProfile::where('user_id', $user->id)->first();
        
        return view('admin.profile.edit', [
            'user' => $user,
            'profile' => $profile
        ]);
    }
    
    /**
     * Update the admin profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'bsn' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);
        
        // Update user name
        $user->name = $validatedData['name'];
        $user->save();
        
        // Find or create admin profile
        $profile = AdminProfile::firstOrNew(['user_id' => $user->id]);
        
        // Update profile details
        $profile->address = $validatedData['address'];
        $profile->city = $validatedData['city'];
        $profile->date_of_birth = $validatedData['date_of_birth'];
        $profile->bsn = $validatedData['bsn'];
        $profile->phone = $validatedData['phone'];
        $profile->save();
        
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profiel succesvol bijgewerkt.');
    }
}
