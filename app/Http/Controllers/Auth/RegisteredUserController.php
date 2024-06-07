<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WilayahService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    protected $wilayahService;

    public function __construct(WilayahService $wilayahService)
    {
        $this->wilayahService = $wilayahService;
    }
    public function create(): Response
    {
        $provinces = $this->wilayahService->getProvinces();

        return Inertia::render('Auth/Register', [
            'provinces' => $provinces
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'provinsi' => $request->provinsi['name'],
            'provinsi_id' => $request->provinsi['id'],
            'kabupaten' => $request->kabupaten['name'],
            'kabupaten_id' => $request->kabupaten['id'],
            'kecamatan' => $request->kecamatan['name'],
            'kecamatan_id' => $request->kecamatan['id'],
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
