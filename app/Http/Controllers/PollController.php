<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePollRequest;
use App\Http\Requests\StoreVoteRequest;
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

        return redirect()->to($poll->admin_url)->with('success', 'Poll created successfully!');
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

    /**
     * Display the participate page for voting.
     */
    public function participate(string $participant_token): View
    {
        $poll = Poll::where('participant_token', $participant_token)
            ->with(['user', 'options', 'votes'])
            ->firstOrFail();

        return view('polls.participate', ['poll' => $poll]);
    }

    /**
     * Display the admin page for poll management.
     */
    public function admin(string $admin_token): View
    {
        $poll = Poll::where('admin_token', $admin_token)
            ->with(['user', 'options', 'votes'])
            ->firstOrFail();

        return view('polls.admin', ['poll' => $poll]);
    }

    /**
     * Store a vote for a poll.
     */
    public function vote(StoreVoteRequest $request, string $participant_token): RedirectResponse
    {
        $poll = Poll::where('participant_token', $participant_token)
            ->with('options')
            ->firstOrFail();

        $data = $request->validated();

        DB::transaction(function () use ($poll, $data) {
            // Delete existing votes from this voter
            $poll->votes()->where('voter_name', $data['voter_name'])->delete();

            // Create new votes
            foreach ($data['votes'] as $optionId => $voteType) {
                $poll->votes()->create([
                    'poll_option_id' => $optionId,
                    'voter_name' => $data['voter_name'],
                    'vote_type' => $voteType,
                ]);
            }
        });

        return redirect()
            ->route('polls.participate', $participant_token)
            ->with('success', 'Your vote has been recorded!');
    }
}
