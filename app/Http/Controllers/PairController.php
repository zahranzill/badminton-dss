<?php

namespace App\Http\Controllers;

use App\Models\Pair;
use App\Models\Player;
use Illuminate\Http\Request;

class PairController extends Controller
{
    public function index(Request $request)
    {
        $query = Pair::with(['player1', 'player2']);

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('pair_type', $request->type);
        }

        $pairs = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('pairs.index', compact('pairs'));
    }

    public function create()
    {
        $players = Player::where('is_active', true)->orderBy('name')->get();
        return view('pairs.create', compact('players'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'player_1_id' => 'required|exists:players,id',
            'player_2_id' => 'required|exists:players,id|different:player_1_id',
            'pair_type' => 'required|in:GD_PUTRA,GD_PUTRI,GD_CAMPURAN',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama pasangan ganda wajib diisi.',
            'name.max' => 'Nama pasangan maksimal 255 karakter.',
            'player_1_id.required' => 'Pemain 1 wajib dipilih.',
            'player_1_id.exists' => 'Pemain 1 tidak valid.',
            'player_2_id.required' => 'Pemain 2 wajib dipilih.',
            'player_2_id.exists' => 'Pemain 2 tidak valid.',
            'player_2_id.different' => 'Pemain 2 harus berbeda dari Pemain 1.',
            'pair_type.required' => 'Jenis ganda wajib dipilih.',
            'pair_type.in' => 'Pilihan jenis ganda tidak valid.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Check if pair combination A-B or B-A already exists
        $exists = Pair::where(function($q) use ($request) {
            $q->where('player_1_id', $request->player_1_id)
              ->where('player_2_id', $request->player_2_id);
        })->orWhere(function($q) use ($request) {
            $q->where('player_1_id', $request->player_2_id)
              ->where('player_2_id', $request->player_1_id);
        })->exists();

        if ($exists) {
            return back()->withErrors(['player_2_id' => 'Pasangan dengan kombinasi kedua pemain ini sudah terdaftar.'])->withInput();
        }

        Pair::create($validated);

        return redirect()->route('pairs.index')->with('success', 'Data pasangan ganda berhasil ditambahkan.');
    }

    public function show(Pair $pair)
    {
        $pair->load(['player1', 'player2', 'matches']);
        return view('pairs.show', compact('pair'));
    }

    public function edit(Pair $pair)
    {
        $players = Player::where('is_active', true)
                         ->orWhere('id', $pair->player_1_id)
                         ->orWhere('id', $pair->player_2_id)
                         ->orderBy('name')
                         ->get();
        return view('pairs.edit', compact('pair', 'players'));
    }

    public function update(Request $request, Pair $pair)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'player_1_id' => 'required|exists:players,id',
            'player_2_id' => 'required|exists:players,id|different:player_1_id',
            'pair_type' => 'required|in:GD_PUTRA,GD_PUTRI,GD_CAMPURAN',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama pasangan ganda wajib diisi.',
            'name.max' => 'Nama pasangan maksimal 255 karakter.',
            'player_1_id.required' => 'Pemain 1 wajib dipilih.',
            'player_1_id.exists' => 'Pemain 1 tidak valid.',
            'player_2_id.required' => 'Pemain 2 wajib dipilih.',
            'player_2_id.exists' => 'Pemain 2 tidak valid.',
            'player_2_id.different' => 'Pemain 2 harus berbeda dari Pemain 1.',
            'pair_type.required' => 'Jenis ganda wajib dipilih.',
            'pair_type.in' => 'Pilihan jenis ganda tidak valid.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Check duplicate pair logic excluding current pair
        $exists = Pair::where('id', '!=', $pair->id)
            ->where(function($q) use ($request) {
                $q->where(function($inner) use ($request) {
                    $inner->where('player_1_id', $request->player_1_id)
                          ->where('player_2_id', $request->player_2_id);
                })->orWhere(function($inner) use ($request) {
                    $inner->where('player_1_id', $request->player_2_id)
                          ->where('player_2_id', $request->player_1_id);
                });
            })->exists();

        if ($exists) {
            return back()->withErrors(['player_2_id' => 'Pasangan dengan kombinasi kedua pemain ini sudah terdaftar.'])->withInput();
        }

        $pair->update($validated);

        return redirect()->route('pairs.index')->with('success', 'Data pasangan ganda berhasil diperbarui.');
    }

    public function destroy(Pair $pair)
    {
        if ($pair->matches()->exists()) {
            return redirect()->route('pairs.index')->with('error', 'Pasangan ganda tidak dapat dihapus karena sudah memiliki data riwayat pertandingan.');
        }

        $pair->delete();

        return redirect()->route('pairs.index')->with('success', 'Data pasangan ganda berhasil dihapus.');
    }
}
