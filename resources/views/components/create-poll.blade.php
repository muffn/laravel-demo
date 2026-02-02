<x-slot:title>
    Create Poll
</x-slot:title>

<div class="max-w-2xl mx-auto my-8">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h1 class="text-2xl font-bold mb-2">Create a New Poll</h1>
            <p class="text-sm text-base-content/60 mb-4">Add a title, optional description and one or more options.</p>

            <form action="{{ route('polls.store') }}" method="POST" novalidate>
                @csrf

                <div class="form-control mb-4">
                    <label class="label" for="title">
                        <span class="label-text">Title</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        class="input input-bordered w-full @error('title') input-error @enderror"
                        placeholder="Enter poll title"
                        required
                        maxlength="255"
                    />
                    @error('title')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control mb-4">
                    <label class="label" for="description">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        class="textarea textarea-bordered w-full @error('description') input-error @enderror"
                        rows="3"
                        placeholder="Optional description"
                        maxlength="1000"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="mb-2 flex items-center justify-between">
                    <h2 class="font-semibold">Options</h2>
                    <button
                        type="button"
                        id="add-option"
                        class="btn btn-sm btn-outline"
                    >+ Add Option</button>
                </div>

                @error('options')
                    <div class="alert alert-error mb-2">
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <div id="options-list" class="space-y-2">
                    @php
                        $oldOptions = old('options', []);
                        $initial = count($oldOptions) ? $oldOptions : ['', '', ''];
                    @endphp

                    @foreach ($initial as $idx => $opt)
                        <div class="flex items-center gap-2 option-row">
                            <div class="form-control flex-1">
                                <input
                                    type="text"
                                    name="options[]"
                                    value="{{ $opt }}"
                                    class="input input-bordered w-full @error('options.' . $idx) input-error @enderror"
                                    placeholder="Option text"
                                    required
                                    maxlength="255"
                                />
                                @error('options.' . $idx)
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>
                            <button
                                type="button"
                                class="btn btn-square btn-sm btn-ghost remove-option"
                                aria-label="Remove option"
                            >✕</button>
                        </div>
                    @endforeach
                </div>

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

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function createOptionRow(value = '') {
        const wrapper = document.createElement('div');
        wrapper.className = 'flex items-center gap-2 option-row';
        wrapper.innerHTML = `
            <div class="form-control flex-1">
                <input
                    type="text"
                    name="options[]"
                    value="${escapeHtml(value)}"
                    class="input input-bordered w-full"
                    placeholder="Option text"
                    required
                    maxlength="255"
                />
            </div>
            <button
                type="button"
                class="btn btn-square btn-sm btn-ghost remove-option"
                aria-label="Remove option"
            >✕</button>
        `;
        return wrapper;
    }

    addBtn.addEventListener('click', function () {
        const newRow = createOptionRow();
        list.appendChild(newRow);
        newRow.querySelector('input').focus();
    });

    list.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.remove-option');
        if (!removeBtn) {
            return;
        }

        const rows = list.querySelectorAll('.option-row');
        if (rows.length <= 1) {
            return;
        }

        removeBtn.closest('.option-row').remove();
    });
})();
</script>
