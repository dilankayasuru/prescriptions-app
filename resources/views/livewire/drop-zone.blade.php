@php
    $maxFiles = $maxFiles ?? 1;
    $maxSize = $maxSize ?? 5242880;
    $hintText = str_replace([':count', ':sizeMB'], [$maxFiles, intval($maxSize / 1024 / 1024)], $hint);
    $multipleAttr = $multiple ? 'multiple' : '';
    $uid = uniqid();
    $inputId = 'dz_input_' . $uid;
    $containerId = 'dz_container_' . $uid;
    $previewId = 'dz_preview_' . $uid;
    $errorId = 'dz_error_' . $uid;
@endphp

<label id="{{ $containerId }}" class="w-full block" wire:ignore>
    <div
        class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-200 dark:border-neutral-700 rounded-md {{ $heightClass }} bg-white dark:bg-neutral-800 text-center p-6">
        <x-dynamic-component :component="'flux::icon.' . $icon" class="h-10 w-10 text-gray-400 dark:text-gray-300 mb-3" />

        <p class="text-sm font-medium text-gray-900 dark:text-white">Drag &amp; drop files here</p>
        <p class="text-sm text-gray-500 dark:text-gray-400">or <span
                class="text-blue-600 dark:text-blue-400 underline">click to browse</span></p>

        <input id="{{ $inputId }}" type="file" name="{{ $name }}" {{ $multipleAttr }}
            accept="{{ $accept }}" class="sr-only" data-max-files="{{ $maxFiles }}"
            data-max-size="{{ $maxSize }}" data-accept="{{ $accept }}" />
    </div>

    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ $hintText }}</p>

    <div id="{{ $errorId }}" class="mt-2 text-xs text-red-600 dark:text-red-400 hidden"></div>

    <div id="{{ $previewId }}" class="mt-3 grid grid-cols-3 gap-2"></div>
</label>

<script wire:ignore>
    (function() {
        const input = document.getElementById('{{ $inputId }}');
        if (!input) return;

        const container = document.getElementById('{{ $containerId }}');
        const preview = document.getElementById('{{ $previewId }}');
        const errorEl = document.getElementById('{{ $errorId }}');
        const maxFiles = parseInt(input.dataset.maxFiles || '{{ $maxFiles }}', 10);
        const maxSize = parseInt(input.dataset.maxSize || '{{ $maxSize }}', 10);

        function showError(msg) {
            errorEl.textContent = msg;
            errorEl.classList.remove('hidden');
            setTimeout(() => errorEl.classList.add('hidden'), 6000);
        }

        function renderPreviews(files) {
            preview.innerHTML = '';
            Array.from(files).forEach((file, idx) => {
                const url = URL.createObjectURL(file);
                const wrapper = document.createElement('div');
                wrapper.className = 'relative rounded overflow-hidden border bg-white dark:bg-neutral-700';

                const img = document.createElement('img');
                img.src = url;
                img.alt = file.name;
                img.className = 'object-cover w-full h-24';

                const rm = document.createElement('button');
                rm.type = 'button';
                rm.className =
                    'absolute top-1 right-1 bg-black bg-opacity-50 text-white rounded-full h-4 w-4 flex items-center justify-center line-space-0';
                rm.title = 'Remove';
                rm.innerHTML = '×';
                rm.addEventListener('click', function(e) {
                    e.preventDefault();
                    removeFileAtIndex(idx);
                });

                wrapper.appendChild(img);
                wrapper.appendChild(rm);
                preview.appendChild(wrapper);

                img.addEventListener('load', () => URL.revokeObjectURL(url));
            });
        }

        function setInputFiles(fileList) {
            const dt = new DataTransfer();
            fileList.forEach(f => dt.items.add(f));
            input.files = dt.files;
            renderPreviews(input.files);
        }

        function removeFileAtIndex(index) {
            const current = Array.from(input.files);
            if (index < 0 || index >= current.length) return;
            current.splice(index, 1);
            setInputFiles(current);
        }

        function validateAndSet(files) {
            const out = [];
            if (files.length > maxFiles) {
                showError('You can upload up to ' + maxFiles + ' files.');
            }

            Array.from(files).slice(0, maxFiles).forEach(file => {
                if (!file.type || !file.type.startsWith('image/')) {
                    showError(file.name + ' is not an image.');
                    return;
                }
                if (file.size > maxSize) {
                    showError(file.name + ' is larger than ' + (maxSize / 1024 / 1024).toFixed(0) + 'MB.');
                    return;
                }
                out.push(file);
            });

            setInputFiles(out);
        }

        input.addEventListener('change', function(e) {
            validateAndSet(e.target.files);
        });

        ['dragenter', 'dragover'].forEach(ev => {
            container.addEventListener(ev, function(e) {
                e.preventDefault();
                e.stopPropagation();
                container.classList.add('ring-2', 'ring-blue-300');
            });
        });
        ['dragleave', 'drop'].forEach(ev => {
            container.addEventListener(ev, function(e) {
                e.preventDefault();
                e.stopPropagation();
                container.classList.remove('ring-2', 'ring-blue-300');
            });
        });

        container.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            if (!dt || !dt.files) return;
            validateAndSet(dt.files);
        });
    })();
</script>
