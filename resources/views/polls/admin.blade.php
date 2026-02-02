<x-layout>
    <x-slot:title>
        Admin - {{ $poll->title }}
    </x-slot:title>

    <div class="max-w-4xl mx-auto">
        <div class="card bg-base-100 shadow mb-4">
            <div class="card-body">
                <h1 class="card-title text-2xl font-bold text-primary">Poll Admin</h1>
                <p class="text-base-content/70">Manage your poll and share the links below.</p>

                <div class="divider my-2"></div>

                <div class="space-y-4">
                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Participant Link</span>
                        </label>
                        <p class="text-sm text-base-content/60 mb-2">Share this link with people you want to vote on your poll.</p>
                        <div class="join w-full">
                            <input type="text" value="{{ $poll->participant_url }}" readonly class="input input-bordered join-item w-full" id="participant-url">
                            <button class="btn btn-primary join-item copy-btn" onclick="copyToClipboard(this, 'participant-url')">
                                Copy
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Admin Link</span>
                        </label>
                        <p class="text-sm text-base-content/60 mb-2">Keep this link private. Use it to manage your poll.</p>
                        <div class="join w-full">
                            <input type="text" value="{{ $poll->admin_url }}" readonly class="input input-bordered join-item w-full" id="admin-url">
                            <button class="btn btn-secondary join-item copy-btn" onclick="copyToClipboard(this, 'admin-url')">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-poll :poll="$poll" />
    </div>

    <script>
        function copyToClipboard(btn, elementId) {
            const input = document.getElementById(elementId);
            navigator.clipboard.writeText(input.value);

            const originalText = btn.textContent;
            btn.textContent = 'Copied!';

            setTimeout(function() {
                btn.textContent = originalText;
            }, 2000);
        }
    </script>
</x-layout>
