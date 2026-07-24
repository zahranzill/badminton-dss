<?php

namespace App\Http\Controllers;

use App\Models\MatchGame;
use App\Models\Rally;
use Illuminate\Http\Request;

class RallyController extends Controller
{
    public function index(MatchGame $match)
    {
        if ($match->status !== 'Draft') {
            return redirect()->route('matches.show', $match->id)->with('error', 'Rally hanya dapat diubah pada pertandingan yang berstatus Draft.');
        }

        $match->load(['pair.player1', 'pair.player2', 'rallies.errorPlayer']);

        return view('rallies.index', compact('match'));
    }

    public function store(Request $request, MatchGame $match)
    {
        if ($match->status !== 'Draft') {
            return redirect()->route('matches.show', $match->id)->with('error', 'Rally hanya dapat ditambahkan pada pertandingan yang berstatus Draft.');
        }

        $validated = $request->validate([
            'set_number' => 'required|integer|min:1|max:3',
            'rally_number' => 'required|integer|min:1',
            'pair_score' => 'required|integer|min:0',
            'opponent_score' => 'required|integer|min:0',
            'point_winner' => 'required|in:Pasangan,Lawan',
            'point_result' => 'required|in:Winner,Error Lawan,Error Sendiri',
            'error_type' => 'nullable|required_if:point_result,Error Sendiri|in:Serve,Smash,Drive,Lift,Lob,Net,Out,Timing,Footwork,Miskomunikasi,Drop Shot,Netting,Defence,Angin lapangan,Lantai licin,Cahaya silau/redup,Raket patah,Senar putus,Shuttlecock rusak,Human error (wasit)',
            'error_player_id' => 'nullable|required_if:point_result,Error Sendiri|exists:players,id',
            'stroke_count' => 'nullable|integer|min:1',
            'rally_duration' => 'nullable|integer|min:1',
            'is_critical_point' => 'nullable|boolean',
            'remarks' => 'nullable|string',
        ], [
            'set_number.required' => 'Nomor set wajib diisi.',
            'rally_number.required' => 'Nomor rally wajib diisi.',
            'pair_score.required' => 'Skor pasangan wajib diisi.',
            'opponent_score.required' => 'Skor lawan wajib diisi.',
            'point_winner.required' => 'Pihak pemenang poin wajib dipilih.',
            'point_result.required' => 'Hasil poin wajib dipilih.',
            'error_type.required_if' => 'Jenis error wajib dipilih jika poin didapat dari error sendiri.',
            'error_player_id.required_if' => 'Pemain yang melakukan error wajib dipilih jika poin didapat dari error sendiri.',
        ]);

        $validated['is_critical_point'] = $request->has('is_critical_point') ? true : false;
        $validated['match_game_id'] = $match->id;

        // Auto-detect critical point: if score is >= 18 for either side, it's typically critical
        // But the prompt says "is_critical_point, yes or no" (input manual, typically checkbox)
        // We will default to what was submitted.

        $rally = Rally::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            $match->load(['rallies.errorPlayer']);
            return response()->json([
                'success' => true,
                'message' => 'Rally berhasil ditambahkan.',
                'rallies' => $match->rallies->sortBy([['set_number', 'desc'], ['rally_number', 'desc']])->values(),
            ]);
        }

        return redirect()->route('rallies.index', $match->id)
            ->withInput(['set_number' => $validated['set_number']])
            ->with('success', 'Rally berhasil ditambahkan.');
    }

    public function edit(MatchGame $match, Rally $rally)
    {
        if ($match->status !== 'Draft') {
            return redirect()->route('matches.show', $match->id)->with('error', 'Rally hanya dapat diubah pada pertandingan yang berstatus Draft.');
        }

        $match->load(['pair.player1', 'pair.player2']);

        return view('rallies.edit', compact('match', 'rally'));
    }

    public function update(Request $request, MatchGame $match, Rally $rally)
    {
        if ($match->status !== 'Draft') {
            return redirect()->route('matches.show', $match->id)->with('error', 'Rally hanya dapat diperbarui pada pertandingan yang berstatus Draft.');
        }

        $validated = $request->validate([
            'set_number' => 'required|integer|min:1|max:3',
            'rally_number' => 'required|integer|min:1',
            'pair_score' => 'required|integer|min:0',
            'opponent_score' => 'required|integer|min:0',
            'point_winner' => 'required|in:Pasangan,Lawan',
            'point_result' => 'required|in:Winner,Error Lawan,Error Sendiri',
            'error_type' => 'nullable|required_if:point_result,Error Sendiri|in:Serve,Smash,Drive,Lift,Lob,Net,Out,Timing,Footwork,Miskomunikasi,Drop Shot,Netting,Defence,Angin lapangan,Lantai licin,Cahaya silau/redup,Raket patah,Senar putus,Shuttlecock rusak,Human error (wasit)',
            'error_player_id' => 'nullable|required_if:point_result,Error Sendiri|exists:players,id',
            'stroke_count' => 'nullable|integer|min:1',
            'rally_duration' => 'nullable|integer|min:1',
            'is_critical_point' => 'nullable|boolean',
            'remarks' => 'nullable|string',
        ], [
            'set_number.required' => 'Nomor set wajib diisi.',
            'rally_number.required' => 'Nomor rally wajib diisi.',
            'pair_score.required' => 'Skor pasangan wajib diisi.',
            'opponent_score.required' => 'Skor lawan wajib diisi.',
            'point_winner.required' => 'Pihak pemenang poin wajib dipilih.',
            'point_result.required' => 'Hasil poin wajib dipilih.',
            'error_type.required_if' => 'Jenis error wajib dipilih jika poin didapat dari error sendiri.',
            'error_player_id.required_if' => 'Pemain yang melakukan error wajib dipilih jika poin didapat dari error sendiri.',
        ]);

        $validated['is_critical_point'] = $request->has('is_critical_point') ? true : false;

        $rally->update($validated);

        return redirect()->route('rallies.index', $match->id)
            ->withInput(['set_number' => $validated['set_number']])
            ->with('success', 'Data rally berhasil diperbarui.');
    }

    public function destroy(MatchGame $match, Rally $rally)
    {
        if ($match->status !== 'Draft') {
            return redirect()->route('matches.show', $match->id)->with('error', 'Rally hanya dapat dihapus pada pertandingan yang berstatus Draft.');
        }

        $rally->delete();

        return redirect()->route('rallies.index', $match->id)->with('success', 'Rally berhasil dihapus.');
    }
}
