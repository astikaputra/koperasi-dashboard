<?php

namespace App\Http\Controllers;

use App\Models\MarkupOverhead;
use Illuminate\Http\Request;

class OverheadController extends Controller
{
    public function index()
    {
        $data = MarkupOverhead::orderBy('bulan', 'DESC')->get();
        return view('overhead.index', compact('data'));
    }

    public function create()
    {
        return view('overhead.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
            'sewa_ruangan' => 'required|numeric',
            'service_charge' => 'required|numeric',
            'operasional' => 'required|numeric',
        ]);

        MarkupOverhead::create($request->all());

        return redirect()->route('overhead.index')
            ->with('success', 'Data overhead berhasil disimpan.');
    }
}
