<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Middleware\EnsureTeamMembership;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::inertia('/', 'welcome')->name('home');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::post('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
    Route::delete('invitations/{invitation}', [TeamInvitationController::class, 'decline'])->name('invitations.decline');
});

/*
|--------------------------------------------------------------------------
| Students
|--------------------------------------------------------------------------
| NOTE: /students/create must be declared BEFORE /students/{id},
| otherwise "create" is treated as an {id}.
*/

Route::get('/students', function () {
    return view('student.list', [
        'students' => Student::all(),
    ]);
})->name('students.index');

Route::get('/students/create', function () {
    return view('student.create');
})->name('students.create');

Route::post('/students', function (Request $request) {
    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|max:255|unique:students,email',
        'phone'         => 'required|string|max:20',
        'address'       => 'nullable|string|max:500',
        'date_of_birth' => 'nullable|date',
    ]);

    $student = Student::create($validated);

    return redirect()->route('students.index')
        ->with('success', "Student {$student->name} created successfully!");
})->name('students.store');

Route::get('/students/{id}', function ($id) {
    return view('student.detail', [
        'student' => Student::findOrFail($id),
    ]);
})->name('students.show');

Route::get('/students/{id}/edit', function ($id) {
    return view('student.edit', [
        'student' => Student::findOrFail($id),
    ]);
})->name('students.edit');

Route::put('/students/{id}', function (Request $request, $id) {
    $student = Student::findOrFail($id);

    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => ['required', 'email', 'max:255', Rule::unique('students')->ignore($student->id)],
        'phone'         => 'required|string|max:20',
        'address'       => 'nullable|string|max:500',
        'date_of_birth' => 'nullable|date',
    ]);

    $student->update($validated);

    return redirect()->route('students.show', $student->id)
        ->with('success', 'Student updated successfully!');
})->name('students.update');

Route::delete('/students/{id}', function ($id) {
    $student = Student::findOrFail($id);
    $student->delete();

    return redirect()->route('students.index')
        ->with('success', 'Student deleted successfully!');
})->name('students.destroy');

require __DIR__.'/settings.php';