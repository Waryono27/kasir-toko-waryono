<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $pelanggans = pelanggan::orderBy('id')
            ->when($search, function ($q, $search) {
                return $q->where('nama', 'like', "%{$search}%");
            })
            ->paginate();

        if ($search) $pelanggans->appends(['search' => $search]);

        return view('pelanggan.index', [
            'pelanggans' => $pelanggans
        ]);
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelanggan.create');
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'max:100', 'regex:/^[^0-9]+$/'], // Tambahkan aturan regex untuk memastikan tidak ada angka
            'alamat' => ['nullable', 'max:500'],
            'nomor_tlp' => ['nullable', 'max:14']
        ], [
        'nama.regex' => 'Nama tidak boleh mengandung angka.',
        ]);

        Pelanggan::create($request->all());
        
        return redirect()->route('pelanggan.index')->with('store','success');
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $pelanggan)
    {
        abort(404);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', [
            'pelanggan' => $pelanggan
        ]);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama' => ['required', 'max:100', 'regex:/^[^0-9]+$/'], // Tambahkan aturan regex untuk memastikan tidak ada angka
            'alamat' => ['nullable', 'max:500'],
            'nomor_tlp' => ['nullable', 'max:14']
        ], [
        'nama.regex' => 'Nama tidak boleh mengandung angka.',
        ]);

        $pelanggan->update($request->all());

        return redirect()->route('pelanggan.index')->with('update', 'success');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return back()->with('destroy', 'success');
        //
    }
}
