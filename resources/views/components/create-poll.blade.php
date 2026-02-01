<x-slot:title>
    Create Poll
</x-slot:title>

<div class="max-w-2xl mx-auto my-8">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h1 class="text-2xl font-bold mb-2">Create a New Poll</h1>
            <p class="text-sm text-base-content/60 mb-4">Add a title, optional description and one or more options.</p>

            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <div>
                        <strong>There were some problems with your input.</strong>
                        <ul class="mt-2 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('polls.store') }}" method="POST" novalidate>
                @csrf
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Title</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        class="input input-bordered w-full"
                        placeholder="Enter poll title"
                        required
                    />
                    @error('title')
                    <p class="text-sm text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea
                        name="description"
                        class="textarea textarea-bordered w-full"
                        rows="3"
                        placeholder="Optional description"
                    >{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-sm text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-2 flex items-center justify-between">
                    <h2 class="font-semibold">Options</h2>
                    <button
                        type="button"
                        id="add-option"
                        class="btn btn-sm btn-outline"
                    >+ Add Option
                    </button>
                </div>

                <div id="options-list" class="space-y-2">
                    @php
                        $oldOptions = old('options', []);
                        $initial = count($oldOptions) ? $oldOptions : ['', '', ''];
                    @endphp

                    @foreach ($initial as $idx => $opt)
                        <div class="flex items-center gap-2" data-index="{{ $idx }}">
                            <input
                                type="text"
                                name="options[]"
                                value="{{ $opt }}"
                                class="input input-bordered w-full"
                                placeholder="Option text"
                                required
                            />
                            <button type="button" class="btn btn-square btn-sm btn-ghost remove-option"
                                    aria-label="Remove option">
                                ✕
                            </button>
                        </div>
                    @endforeach
                </div>

                @error('options')
                <p class="text-sm text-error mt-2">{{ $message }}</p>
                @enderror

                <div class="divider my-4"></div>

                <div class="flex gap-2 justify-end">
                    <a href="{{ url()->previous() }}" class="btn btn-ghost">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Poll</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const addBtn = document.getElementById('add-option');
        const list = document.getElementById('options-list');

        function makeOption(value = '') {
            const wrapper = document.createElement('div');
            wrapper.className = 'flex items-center gap-2';
            wrapper.innerHTML = `
                    <input type="text" name="options[]" value="${escapeHtml(value)}" class="input input-bordered w-full" placeholder="Option text" required />
                    <button type="button" class="btn btn-square btn-sm btn-ghost remove-option" aria-label="Remove option">✕</button>
                `;
            return wrapper;
        }

        function escapeHtml(unsafe) {
            return String(unsafe)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        addBtn.addEventListener('click', function () {
            list.appendChild(makeOption(''));
        });

        list.addEventListener('click', function (e) {
            if (e.target && e.target.matches('.remove-option')) {
                const rows = list.querySelectorAll('input[name="options[]"]');
                // keep at least one option
                if (rows.length <= 1) {
                    return;
                }
                e.target.closest('div').remove();
            }
        });
    })();
</script>
