<x-layout>
    <x-slot:title>
        Vote - {{ $poll->title }}
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow mb-4">
            <div class="card-body">
                <h1 class="card-title text-2xl font-bold text-primary">{{ $poll->title }}</h1>
                @if($poll->description)
                    <p class="text-base-content/70 italic">{{ $poll->description }}</p>
                @endif
                <p class="mt-2 text-sm text-base-content/50">
                    Created {{ $poll->created_at->diffForHumans() }}
                    by {{ $poll->user ? $poll->user->name : 'Anonymous' }}
                </p>
            </div>
        </div>

        <div class="card bg-base-100 shadow mb-4">
            <div class="card-body">
                <h2 class="card-title text-lg font-semibold">Cast Your Vote</h2>

                <form action="{{ route('polls.vote', $poll->participant_token) }}" method="POST">
                    @csrf

                    <div class="form-control mb-4">
                        <label class="label">
                            <span class="label-text font-semibold">Your Name</span>
                        </label>
                        <input type="text" name="voter_name" value="{{ old('voter_name') }}" placeholder="Enter your name" class="input input-bordered w-full @error('voter_name') input-error @enderror" required>
                        @error('voter_name')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="divider my-2"></div>

                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="bg-base-200">
                                    <th class="font-bold">Option</th>
                                    <th class="font-bold text-center">Yes</th>
                                    <th class="font-bold text-center">Maybe</th>
                                    <th class="font-bold text-center">No</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($poll->options as $option)
                                    <tr class="hover">
                                        <td class="font-semibold">{{ $option->option_text }}</td>
                                        <td class="text-center">
                                            <input type="radio" name="votes[{{ $option->id }}]" value="yes" class="radio radio-success" {{ old("votes.{$option->id}") === 'yes' ? 'checked' : '' }} required>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="votes[{{ $option->id }}]" value="maybe" class="radio radio-warning" {{ old("votes.{$option->id}") === 'maybe' ? 'checked' : '' }}>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="votes[{{ $option->id }}]" value="no" class="radio radio-error" {{ old("votes.{$option->id}") === 'no' ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @error('votes')
                        <div class="alert alert-error mt-4">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-full">Submit Vote</button>
                    </div>
                </form>
            </div>
        </div>

        @if($poll->votes->isNotEmpty())
            <x-poll :poll="$poll" />
        @endif
    </div>
</x-layout>
