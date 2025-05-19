<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the student's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        // Create student profile if it doesn't exist yet
        if (!$student) {
            $student = Student::create([
                'user_id' => $user->id,
            ]);
        }
        
        return view('student.profile.edit', [
            'user' => $user,
            'student' => $student,
        ]);
    }
    
    /**
     * Update the student's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:15',
        ]);
        
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            $student = Student::create([
                'user_id' => $user->id,
            ]);
        }
        
        // Update user name
        $user = \App\Models\User::find($user->id);
        $user->name = $validatedData['name'];
        $user->save();
        
        // Update student profile
        $student->address = $validatedData['address'];
        $student->city = $validatedData['city'];
        $student->postal_code = $validatedData['postal_code'];
        $student->date_of_birth = $validatedData['date_of_birth'];
        $student->phone = $validatedData['phone'];
        $student->save();
        
        return redirect()->route('student.profile.edit')
            ->with('success', 'Je profiel is succesvol bijgewerkt.');
    }
}
