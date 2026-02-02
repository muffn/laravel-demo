<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePollRequest;
use App\Models\Poll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
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
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePollRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $options = $request->validatedOptions();

        if (count($options) === 0) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['options' => 'Please provide at least one non-empty option.']);
        }

        $poll = DB::transaction(function () use ($data, $options): Poll {
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

        return redirect()->to(url('/'))->with('success', 'Poll created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePollRequest $request, string $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }
}
