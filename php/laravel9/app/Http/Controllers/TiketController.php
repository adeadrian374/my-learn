<?php

namespace App\Http\Controllers;

use App\Models\Bayar;
use App\Models\Pengguna;
use App\Models\Tiket;
use App\Models\Transportasi;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allTiket = Tiket::all();
        return view('tiket.index', compact('allTiket'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pengguna = Pengguna::all();
        $transportasi = Transportasi::all();
        $bayar = Bayar::all();
        return view('tiket.create', compact('pengguna', 'transportasi', 'bayar'));
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
            'asal' => 'required|max:100',
            'tujuan' => 'required|max:100',
            'pengguna_id' => 'required',
            'transportasi_id' => 'required',
            'bayar_id' => 'required',
        ]);

        // simpan data
        Tiket::create($validatedData);

        // redirect data ke tiket indeks
        return redirect()->route('tiket.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tiket  $tiket
     * @return \Illuminate\Http\Response
     */
    public function show(Tiket $tiket)
    {
        return view('tiket.show', compact('tiket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tiket  $tiket
     * @return \Illuminate\Http\Response
     */
    public function edit(Tiket $tiket)
    {
        $pengguna = Pengguna::all();
        $transportasi = Transportasi::all();
        $bayar = Bayar::all();
        return view('tiket.edit', compact('tiket', 'pengguna', 'transportasi', 'bayar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tiket  $tiket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tiket $tiket)
    {
        // create validasi untuk penyimpanan submit data
        $validatedData = $request->validate([
            'asal' => 'required|max:100',
            'tujuan' => 'required|max:100',
            'pengguna_id' => 'required',
            'transportasi_id' => 'required',
            'bayar_id' => 'required',
        ]);

        // simpan data
        $tiket->update($validatedData);

        // redirect data ke tiket indeks
        return redirect()->route('tiket.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tiket  $tiket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tiket $tiket)
    {
        $tiket->delete();
        // redirect data ke tiket indeks
        return redirect()->route('tiket.index');
    }
}
