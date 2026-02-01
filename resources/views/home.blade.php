<x-layout>
    <x-slot:title>
        Welcome
    </x-slot:title>
    <div class="max-w-2xl mx-auto">
        @foreach($polls as $poll)
            <div class="card bg-base-100 shadow mb-4">
                <div class="card-body">
                    <h2 class="card-title">{{ $poll['title']}}</h2>
                    <p>{{ $poll['description'] }}</p>
                    <table>
                        <thead>
                        <tr>
                            <th>Option</th>
                            <th>Votes</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($poll['options'] as $option)
                            <tr>
                                <td>{{ $option['label'] }}</td>
                                <td>{{ $option['votes'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
        <div class="card bg-base-100 shadow mt-8">
            <div class="card-body">
                <div>
                    <h1 class="text-3xl font-bold">Welcome to Meetkat!</h1>
                    <p class="mt-4 text-base-content/60">This is your brand new Laravel application.</p>
                </div>
            </div>
        </div>
    </div>
</x-layout>
