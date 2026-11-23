<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $data = Agent::query()
            ->when($request->search, function ($q, $search) {
                $q->where(function ($w) use ($search) {
                    $w->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends($request->only('search'));

        if ($request->boolean('ajax')) {
            return view('agents.table', compact('data'));
        }

        return view('agents.index', compact('data'));
    }

    public function create()
    {
        return view('agents.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        Agent::create($validated);

        return redirect()->route('master.agent.index')
            ->with('success', 'Agent berhasil ditambahkan.');
    }

    public function show(Agent $agent)
    {
        $agent->loadCount('jamaahs');
        $jamaahs = $agent->jamaahs()->latest('id_jamaah')->take(10)->get();

        return view('agents.show', compact('agent', 'jamaahs'));
    }

    public function edit(Agent $agent)
    {
        return view('agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $this->validateData($request);

        $agent->update($validated);

        return redirect()->route('master.agent.index')
            ->with('success', 'Agent berhasil diperbarui.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete(); // soft delete

        return redirect()->route('master.agent.index')
            ->with('success', 'Agent berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama agent wajib diisi.',
            'name.max'      => 'Nama agent maksimal 255 karakter.',
            'phone.max'     => 'No. telepon maksimal 20 karakter.',
        ]);
    }
}
