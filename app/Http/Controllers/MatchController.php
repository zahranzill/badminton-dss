<?php

namespace App\Http\Controllers;

use App\Models\MatchGame;
use App\Models\Pair;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $query = MatchGame::with('pair')->withCount('rallies');

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('opponent_name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pair', function($p) use ($request) {
                      $p->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('result') && $request->result != '') {
            $query->where('result', $request->result);
        }

        $matches = $query->orderBy('match_date', 'desc')->paginate(10)->withQueryString();

        return view('matches.index', compact('matches'));
    }

    public function create()
    {
        $pairs = Pair::where('is_active', true)->orderBy('name')->get();
        return view('matches.create', compact('pairs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'match_date' => 'required|date',
            'pair_id' => 'required|exists:pairs,id',
            'opponent_name' => 'required|string|max:255',
            'match_type' => 'required|in:Persahabatan,Turnamen,Latih Tanding,Lainnya',
            'pair_category' => 'required|in:GD_PUTRA,GD_PUTRI,GD_CAMPURAN',
            'final_score' => 'nullable|string|max:50',
            'result' => 'required|in:Menang,Kalah',
            'notes' => 'nullable|string',
        ], [
            'match_date.required' => 'Tanggal pertandingan wajib diisi.',
            'match_date.date' => 'Format tanggal pertandingan tidak valid.',
            'pair_id.required' => 'Pasangan ganda wajib dipilih.',
            'pair_id.exists' => 'Pasangan ganda tidak valid.',
            'opponent_name.required' => 'Nama lawan wajib diisi.',
            'opponent_name.max' => 'Nama lawan maksimal 255 karakter.',
            'match_type.required' => 'Jenis pertandingan wajib dipilih.',
            'match_type.in' => 'Pilihan jenis pertandingan tidak valid.',
            'pair_category.required' => 'Kategori ganda wajib dipilih.',
            'pair_category.in' => 'Pilihan kategori ganda tidak valid.',
            'final_score.max' => 'Format skor akhir maksimal 50 karakter.',
            'result.required' => 'Hasil pertandingan wajib dipilih.',
            'result.in' => 'Pilihan hasil pertandingan tidak valid.',
        ]);

        $validated['status'] = 'Draft';

        MatchGame::create($validated);

        return redirect()->route('matches.index')->with('success', 'Data pertandingan berhasil dibuat.');
    }

    public function show(MatchGame $match)
    {
        $match->load(['pair.player1', 'pair.player2', 'rallies.errorPlayer']);
        return view('matches.show', compact('match'));
    }

    public function edit(MatchGame $match)
    {
        if ($match->status !== 'Draft') {
            return redirect()->route('matches.index')->with('error', 'Hanya data pertandingan berstatus Draft yang dapat diubah.');
        }

        $pairs = Pair::where('is_active', true)
                     ->orWhere('id', $match->pair_id)
                     ->orderBy('name')
                     ->get();

        return view('matches.edit', compact('match', 'pairs'));
    }

    public function update(Request $request, MatchGame $match)
    {
        if ($match->status !== 'Draft') {
            return redirect()->route('matches.index')->with('error', 'Hanya data pertandingan berstatus Draft yang dapat diperbarui.');
        }

        $validated = $request->validate([
            'match_date' => 'required|date',
            'pair_id' => 'required|exists:pairs,id',
            'opponent_name' => 'required|string|max:255',
            'match_type' => 'required|in:Persahabatan,Turnamen,Latih Tanding,Lainnya',
            'pair_category' => 'required|in:GD_PUTRA,GD_PUTRI,GD_CAMPURAN',
            'final_score' => 'nullable|string|max:50',
            'result' => 'required|in:Menang,Kalah',
            'notes' => 'nullable|string',
        ], [
            'match_date.required' => 'Tanggal pertandingan wajib diisi.',
            'match_date.date' => 'Format tanggal pertandingan tidak valid.',
            'pair_id.required' => 'Pasangan ganda wajib dipilih.',
            'pair_id.exists' => 'Pasangan ganda tidak valid.',
            'opponent_name.required' => 'Nama lawan wajib diisi.',
            'opponent_name.max' => 'Nama lawan maksimal 255 karakter.',
            'match_type.required' => 'Jenis pertandingan wajib dipilih.',
            'match_type.in' => 'Pilihan jenis pertandingan tidak valid.',
            'pair_category.required' => 'Kategori ganda wajib dipilih.',
            'pair_category.in' => 'Pilihan kategori ganda tidak valid.',
            'final_score.max' => 'Format skor akhir maksimal 50 karakter.',
            'result.required' => 'Hasil pertandingan wajib dipilih.',
            'result.in' => 'Pilihan hasil pertandingan tidak valid.',
        ]);

        $match->update($validated);

        return redirect()->route('matches.index')->with('success', 'Data pertandingan berhasil diperbarui.');
    }

    public function destroy(MatchGame $match)
    {
        if ($match->status !== 'Draft') {
            return redirect()->route('matches.index')->with('error', 'Hanya data pertandingan berstatus Draft yang dapat dihapus.');
        }

        $match->delete();

        return redirect()->route('matches.index')->with('success', 'Data pertandingan berhasil dihapus.');
    }
}
