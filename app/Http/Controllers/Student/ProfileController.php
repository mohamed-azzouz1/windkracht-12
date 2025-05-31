<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
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
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        return view('student.profile.edit', [
            'user' => $user,
            'student' => $student
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
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
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
        
        // Update student details
        $student->update([
            'address' => $validatedData['address'],
            'city' => $validatedData['city'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'bsn' => $validatedData['bsn'],
            'phone' => $validatedData['phone'],
        ]);
        
        return redirect()->route('student.profile.edit')
            ->with('success', 'Profiel succesvol bijgewerkt.');
    }
}
