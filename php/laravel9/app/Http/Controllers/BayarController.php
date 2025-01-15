<?php

namespace App\Http\Controllers;

use App\Models\Bayar;
use Illuminate\Http\Request;

class BayarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allBayar = Bayar::all();
        return view('bayar.index', compact('allBayar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bayar.create');
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
            'nama_pembayar' => 'required|max:100',
        ]);

        // simpan data
        Bayar::create($validatedData);

        // redirect data ke bayar indeks
        return redirect()->route('bayar.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bayar  $bayar
     * @return \Illuminate\Http\Response
     */
    public function show(Bayar $bayar)
    {
        return view('bayar.show', compact('bayar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bayar  $bayar
     * @return \Illuminate\Http\Response
     */
    public function edit(Bayar $bayar)
    {
        return view('bayar.edit', compact('bayar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bayar  $bayar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bayar $bayar)
    {
        // create validasi untuk penyimpanan submit data
        $validatedData = $request->validate([
            'nama_pembayar' => 'required|max:100',
        ]);

        // simpan data
        $bayar->update($validatedData);

        // redirect data ke bayar indeks
        return redirect()->route('bayar.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bayar  $bayar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bayar $bayar)
    {
        $bayar->delete();
        // redirect data ke bayar indeks
        return redirect()->route('bayar.index');
    }
}
