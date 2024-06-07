<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\WilayahService;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{

    protected $wilayahService;

    public function __construct(WilayahService $wilayahService)
    {
        $this->wilayahService = $wilayahService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->get();
        return Inertia::render('Users/Users', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $provinces = $this->wilayahService->getProvinces();

        return Inertia::render('Users/Create', [
            'provinces' => $provinces
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email:dns|max:255|unique:users',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'password' => 'required|confirmed',
        ]);

        User::create([
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

        return redirect(route('user.index'));
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        //edit
        $user = User::findOrFail($id);
        $provinces = $this->wilayahService->getProvinces();
        $kabupatens = $this->getRegencies($user->provinsi_id);
        $kecamatans = $this->getDistricts($user->kabupaten_id);

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'provinces' => $provinces,
            'kabupatens' => $kabupatens,
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //update
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email:dns|max:255|unique:users,email,' . $id,
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'password' => 'confirmed',
        ]);

        $user = User::findOrFail($id);

        if (!$request->password) {
            $validated['password'] = $user->password;
        } else {
            $validated['password'] = Hash::make($request->password);
        }

        // Temukan nama berdasarkan ID yang dikirimkan


        $user->update($validated);

        return redirect(route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect(route('user.index'));
    }

    public function getRegencies($provinceId)
    {
        return $this->wilayahService->getRegencies($provinceId);
    }

    public function getDistricts($regencyId)
    {
        return $this->wilayahService->getDistricts($regencyId);
    }
}
