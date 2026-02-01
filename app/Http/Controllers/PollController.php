<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $polls = Poll::with(['user', 'options', 'votes'])
            ->latest()
            ->take(50)
            ->get();
        return view('home', ['polls' => $polls]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'options' => 'required|array|min:1',
            'options.*' => 'string|max:255',
        ]);

        $options = array_values(array_filter(array_map('trim', $data['options'] ?? []), function ($opt) {
            return $opt !== '';
        }));

        if (count($options) === 0) {
            return redirect()->back()->withInput()->withErrors(['options' => 'Please provide at least one non-empty option.']);
        }

        $poll = DB::transaction(function () use ($data, $options) {
            $poll = Poll::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'user_id' => auth()->id(),
            ]);

            $payload = [];
            foreach ($options as $index => $text) {
                $payload[] = [
                    'option_text' => $text,
                    'order' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $poll->options()->createMany($payload);

            return $poll;
        });

        return redirect()->to(url('/'))->with('success', 'Poll created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}


namespace App\Http\Controllers;
