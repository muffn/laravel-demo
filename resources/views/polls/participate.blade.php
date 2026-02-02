<x-layout>
    <x-slot:title>
        Vote - {{ $poll->title }}
    </x-slot:title>

    <div class="max-w-4xl mx-auto">
        <x-poll :poll="$poll" :votable="true" :vote-url="route('polls.vote', $poll->participant_token)" />
    </div>
</x-layout>
