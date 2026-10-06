@props([
    'name',
    'label',
    'value' => null,
    'rows' => 3,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
])

<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700">
        {{ $label }}
        @if ($required)<span class="text-rose-500">*</span>@endif
    </label>
    <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}"
              @if ($required) required @endif
              @if ($placeholder) placeholder="{{ $placeholder }}" @endif
              {{ $attributes->except('class') }}
              class="mt-1.5 w-full rounded-lg border px-3.5 py-2.5 text-sm leading-relaxed outline-none transition placeholder:text-slate-400 focus:ring-2 {{ $errors->has($name) ? 'border-rose-300 bg-rose-50/40 focus:border-rose-400 focus:ring-rose-100' : 'border-slate-200 focus:border-brand-400 focus:ring-brand-100' }}">{{ old($name, $value) }}</textarea>
    @if ($hint)
        <p class="mt-1.5 text-xs text-slate-500">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
    @enderror
</div>
