@props(['poll'])

<div class="card bg-base-100 shadow mb-4">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold text-primary">{{ $poll['title']}}</h2>
        <p class="text-base-content/70 italic">{{ $poll['description'] }}</p>
        <p class="mt-2 text-sm text-base-content/50">Created
            {{ $poll['created_at']->diffForHumans() }}
            by {{ $poll->user ? $poll->user->name : 'Anonymous' }}</p>
        <div class="divider my-2"></div>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                <tr class="bg-primary text-primary-content">
                    <th class="font-bold">Voter</th>
                    @foreach($poll['options'] as $option)
                        <th class="font-bold text-center">{{ $option['option_text'] }}</th>
                    @endforeach
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
                                    <span class="badge badge-success">✓ Yes</span>
                                @elseif($voteType === 'no')
                                    <span class="badge badge-error">✗ No</span>
                                @elseif($voteType === 'maybe')
                                    <span class="badge badge-warning">? Maybe</span>
                                @elseif($voteType === 'no_answer')
                                    <span class="badge badge-ghost">— No Answer</span>
                                @else
                                    <span class="badge badge-outline">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
