@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'invalid-feedback d-block']) }}>
        @foreach ((array) $messages as $message)
            <div class="text-danger small">{{ $message }}</div>
        @endforeach
    </div>
@endif
