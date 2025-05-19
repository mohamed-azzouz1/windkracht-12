<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Instructor;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the instructor's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->first();
        
        if (!$instructor) {
            // Create a basic instructor profile if it doesn't exist
            $instructor = Instructor::create([
                'user_id' => $user->id,
                'is_active' => true
            ]);
        }
        
        return view('instructor.profile.edit', [
            'user' => $user,
            'instructor' => $instructor
        ]);
    }
    
    /**
     * Update the instructor's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'bsn' => 'required|string|max:9',
            'phone' => 'required|string|max:15',
            'certification' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
        ]);
        
        // Update user name
        $user = \App\Models\User::find($user->id);
        $user->name = $validatedData['name'];
        $user->save();
        
        // Update instructor profile
        $instructor->address = $validatedData['address'];
        $instructor->city = $validatedData['city'];
        $instructor->date_of_birth = $validatedData['date_of_birth'];
        $instructor->bsn = $validatedData['bsn'];
        $instructor->phone = $validatedData['phone'];
        $instructor->certification = $validatedData['certification'];
        $instructor->years_of_experience = $validatedData['years_of_experience'];
        $instructor->save();
        
        return redirect()->route('instructor.profile.edit')
            ->with('success', 'Profiel succesvol bijgewerkt.');
    }
}
