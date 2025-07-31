@props(['text' => 'Simpan', 'id' => 'btn-submit', 'loadingText' => 'Menyimpan...'])

<button type="submit" class="btn btn-primary" id="{{ $id }}" data-loading-text="{{ $loadingText }}">
    <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true" id="{{ $id }}-spinner"></span>
    <span id="{{ $id }}-text">{{ $text }}</span>
</button>

@once
    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const forms = document.querySelectorAll("form");

            forms.forEach((form) => {
                form.addEventListener("submit", function () {
                    const submitBtn = form.querySelector("button[type='submit']");

                    if (submitBtn) {
                        const btnId = submitBtn.id;
                        const spinner = document.getElementById(`${btnId}-spinner`);
                        const text = document.getElementById(`${btnId}-text`);
                        const loadingText = submitBtn.getAttribute("data-loading-text") || "Menyimpan...";

                        submitBtn.disabled = true;

                        if (spinner) spinner.classList.remove("d-none");
                        if (text) text.textContent = loadingText;
                    }
                });
            });
        });
    </script>
    @endpush
@endonce
