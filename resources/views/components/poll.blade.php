@props(['poll', 'votable' => false, 'voteUrl' => null])

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold text-primary">{{ $poll['title']}}</h2>
        <p class="text-base-content/70 italic">{{ $poll['description'] }}</p>
        <p class="mt-2 text-sm text-base-content/50">Created
            {{ $poll['created_at']->diffForHumans() }}
            by {{ $poll->user ? $poll->user->name : 'Anonymous' }}</p>
        <div class="divider my-2"></div>

        @if($votable && $voteUrl)
            <form action="{{ $voteUrl }}" method="POST" id="vote-form">
                @csrf
        @endif

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                <tr class="bg-primary text-primary-content">
                    <th class="font-bold">Voter</th>
                    @foreach($poll['options'] as $option)
                        <th class="font-bold text-center">{{ $option['option_text'] }}</th>
                    @endforeach
                    @if($votable)
                        <th></th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($poll['votes']->groupBy('voter_name') as $voterName => $votes)
                    <tr class="hover">
                        <td class="font-semibold">{{ $voterName }}</td>
                        @foreach($poll['options'] as $option)
                            <td class="text-center">
                                @php
                                    $vote = $votes->firstWhere('poll_option_id', $option['id']);
                                    $voteType = $vote?->vote_type;
                                @endphp
                                @if($voteType === 'yes')
                                    <span class="badge badge-success">✅</span>
                                @elseif($voteType === 'no')
                                    <span class="badge badge-error">❌</span>
                                @elseif($voteType === 'maybe')
                                    <span class="badge badge-ghost">⚠️</span>
                                @else
                                    <span class="badge badge-outline">—</span>
                                @endif
                            </td>
                        @endforeach
                        @if($votable)
                            <td></td>
                        @endif
                    </tr>
                @endforeach

                @if($votable)
                    <tr class="bg-base-200">
                        <td>
                            <input
                                type="text"
                                name="voter_name"
                                value="{{ old('voter_name') }}"
                                placeholder="Your name"
                                class="input input-bordered input-sm w-full min-w-32 @error('voter_name') input-error @enderror"
                                required
                            >
                        </td>
                        @foreach($poll['options'] as $option)
                            <td class="text-center">
                                <div class="flex flex-col gap-1 items-center">
                                    <label class="cursor-pointer flex items-center gap-1">
                                        <input type="radio" name="votes[{{ $option['id'] }}]" value="yes" class="radio radio-success radio-xs" {{ old("votes.{$option['id']}") === 'yes' ? 'checked' : '' }} required>
                                        <span class="text-xs">Yes</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center gap-1">
                                        <input type="radio" name="votes[{{ $option['id'] }}]" value="maybe" class="radio radio-warning radio-xs" {{ old("votes.{$option['id']}") === 'maybe' ? 'checked' : '' }}>
                                        <span class="text-xs">Maybe</span>
                                    </label>
                                    <label class="cursor-pointer flex items-center gap-1">
                                        <input type="radio" name="votes[{{ $option['id'] }}]" value="no" class="radio radio-error radio-xs" {{ old("votes.{$option['id']}") === 'no' ? 'checked' : '' }}>
                                        <span class="text-xs">No</span>
                                    </label>
                                </div>
                            </td>
                        @endforeach
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Vote</button>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        @if($votable)
            @error('voter_name')
                <div class="text-error text-sm mt-2">{{ $message }}</div>
            @enderror
            @error('votes')
                <div class="text-error text-sm mt-2">{{ $message }}</div>
            @enderror
        @endif

        @if($votable && $voteUrl)
            </form>
        @endif
    </div>
</div>
