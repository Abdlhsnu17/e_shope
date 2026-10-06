@props([
    'name',
    'label',
    'options' => [],
    'value' => null,
    'required' => false,
    'placeholder' => null,
])

<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
        {{ $label }}
        @if ($required)<span class="text-rose-500">*</span>@endif
    </label>
    <select name="{{ $name }}" id="{{ $name }}"
            @if ($required) required @endif
            {{ $attributes->except('class') }}
            class="mt-1.5 w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm outline-none transition focus:ring-2 {{ $errors->has($name) ? 'border-rose-300 bg-rose-50/40 focus:border-rose-400 focus:ring-rose-100' : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100' }}">
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $key => $teks)
            <option value="{{ $key }}" @selected((string) old($name, $value) === (string) $key)>{{ $teks }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror
</div>
