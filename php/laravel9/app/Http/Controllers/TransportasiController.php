<?php

namespace App\Http\Controllers;

use App\Models\Transportasi;
use Illuminate\Http\Request;

class TransportasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allTransportasi = Transportasi::all();
        return view('transportasi.index', compact('allTransportasi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transportasi.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create validasi untuk penyimpanan submit data
        $validatedData = $request->validate([
            'tipe_transportasi' => 'required|max:100',
        ]);

        // simpan data
        Transportasi::create($validatedData);

        // redirect data ke transportasi indeks
        return redirect()->route('transportasi.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transportasi  $transportasi
     * @return \Illuminate\Http\Response
     */
    public function show(Transportasi $transportasi)
    {
        return view('transportasi.show', compact('transportasi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transportasi  $transportasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transportasi $transportasi)
    {
        return view('transportasi.edit', compact('transportasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transportasi  $transportasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transportasi $transportasi)
    {
        // create validasi untuk penyimpanan submit data
        $validatedData = $request->validate([
            'tipe_transportasi' => 'required|max:100',
        ]);

        // simpan data
        $transportasi->update($validatedData);

        // redirect data ke transportasi indeks
        return redirect()->route('transportasi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transportasi  $transportasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transportasi $transportasi)
    {
        $transportasi->delete();
        // redirect data ke transportasi indeks
        return redirect()->route('transportasi.index');
    }
}
