<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
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
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
            
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',
                'bsn' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'certification' => 'nullable|string|max:255',
                'years_of_experience' => 'nullable|integer|min:0',
                'specialization' => 'nullable|string|max:255',
                'biography' => 'nullable|string|max:5000',
            ]);
            
            // Update user name
            $user->name = $validatedData['name'];
            $user->save();
            
            // Update instructor details
            $instructor->address = $validatedData['address'];
            $instructor->city = $validatedData['city'];
            $instructor->date_of_birth = $validatedData['date_of_birth'];
            $instructor->bsn = $validatedData['bsn'];
            $instructor->phone = $validatedData['phone'];
            $instructor->specialization = $validatedData['specialization'] ?? null;
            $instructor->biography = $validatedData['biography'] ?? null;
            $instructor->certification = $validatedData['certification'] ?? null;
            $instructor->years_of_experience = $validatedData['years_of_experience'] ?? null;
            
            $instructor->save();
            
            DB::commit();
            
            return redirect()->route('instructor.profile.edit')
                ->with('success', 'Profiel succesvol bijgewerkt.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating instructor profile: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het bijwerken van het profiel. Probeer het opnieuw.');
        }
    }
}
