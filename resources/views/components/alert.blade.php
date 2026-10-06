@if (session('success'))
    <div class="mt-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-900 shadow-card">
        <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="mt-5 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm text-rose-900 shadow-card">
        <x-icon name="x-circle" class="mt-0.5 size-5 shrink-0 text-rose-600" />
        <div>
            <p class="font-semibold">Terdapat {{ $errors->count() }} kesalahan pada isian Anda</p>
            <ul class="mt-1.5 list-inside list-disc space-y-1 text-rose-800">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
