<?php

use Inertia\Inertia;
use App\Models\Contact;
use App\Models\InstructorLevel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SchoolUserController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SubjectUserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserInstructorLevelController;
use App\Http\Controllers\WorkExperienceController;
use App\Models\WorkExperience;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::resource('countries', CountryController::class)
        ->only('index');
    Route::resource('cities', CityController::class)
        ->only('index');
    Route::resource('subjects', SubjectController::class)
        ->only(['index', 'store']);


    Route::put('instructor-levels/update', [UserInstructorLevelController::class, 'update'])
        ->name('instructor-levels.update');

    Route::put('subjects-user', [SubjectUserController::class, 'update'])
        ->name('subject-user.update');
    Route::post('school-user', [SchoolUserController::class, 'store'])
        ->name('school-user.store');
    Route::put('school-user', [SchoolUserController::class, 'update'])
        ->name('school-user.update');

    Route::get('companies/suggest/{property}', [SchoolController::class, 'suggest'])
        ->name('companies.suggest');

    Route::prefix('registration')->group(function () {
        Route::get('/select-roles', [RegistrationController::class, 'selectRoleView'])
            ->name('registration.select-roles-view');
        Route::post('/select-roles', [RegistrationController::class, 'selectRoles'])
            ->name('registration.select-roles');

        Route::get('/add-school', [RegistrationController::class, 'addSchoolView'])
            ->name('registration.add-school-view');
        Route::get('/add-subject', [RegistrationController::class, 'addSubjectView'])
            ->name('registration.add-subject-view');

        Route::get('/add-contact', [RegistrationController::class, 'addContactView'])
            ->name('registration.add-contact-view');
        Route::post('/add-contact', [ContactController::class, 'store'])
            ->name('registration.add-contact');

        Route::get('/add-instructor-level', [RegistrationController::class, 'addInstructorLevelView'])
            ->name('registration.add-instructor-level-view');
        Route::post('/add-instructor-level', [UserInstructorLevelController::class, 'store'])
            ->name('registration.add-instructor-level');
    });

    Route::middleware([
        'registration.complete'
    ])->group(function () {
        Route::get('/dashboard', function () {
            return redirect()->route('people.index');
            // return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('people/me', [PeopleController::class, 'showMe'])->name('people.me');
        Route::resource('/people', PeopleController::class)
            ->only(['index', 'show']);

        Route::get('contacts/edit', [ContactController::class, 'edit'])
            ->name('contacts.edit');
        Route::put('contacts/update', [ContactController::class, 'update'])
            ->name('contacts.update');

        Route::get('instructor-levels/edit', [UserInstructorLevelController::class, 'edit'])
            ->name('instructor-levels.edit');

        Route::get('subjects-user', [SubjectUserController::class, 'edit'])
            ->name('subject-user.edit');
        Route::resource('work-experiences', WorkExperienceController::class);

        Route::get('school-user', [SchoolUserController::class, 'edit'])
            ->name('school-user.edit');
    });
});
