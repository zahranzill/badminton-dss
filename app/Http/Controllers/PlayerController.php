<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        if ($request->has('gender') && $request->gender != '') {
            $query->where('gender', $request->gender);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active' ? 1 : 0);
        }

        $players = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('players.index', compact('players'));
    }

    public function create()
    {
        return view('players.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'category' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama pemain wajib diisi.',
            'name.max' => 'Nama pemain maksimal 255 karakter.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'category.max' => 'Kategori maksimal 255 karakter.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Player::create($validated);

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil ditambahkan.');
    }

    public function show(Player $player)
    {
        return view('players.show', compact('player'));
    }

    public function edit(Player $player)
    {
        return view('players.edit', compact('player'));
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'category' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama pemain wajib diisi.',
            'name.max' => 'Nama pemain maksimal 255 karakter.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilihan jenis kelamin tidak valid.',
            'category.max' => 'Kategori maksimal 255 karakter.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $player->update($validated);

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil diperbarui.');
    }

    public function destroy(Player $player)
    {
        // Check if player is used in pairs
        if ($player->pairsAsPlayer1()->exists() || $player->pairsAsPlayer2()->exists()) {
            return redirect()->route('players.index')->with('error', 'Pemain tidak dapat dihapus karena sudah memiliki pasangan ganda.');
        }

        $player->delete();

        return redirect()->route('players.index')->with('success', 'Data pemain berhasil dihapus.');
    }
}
