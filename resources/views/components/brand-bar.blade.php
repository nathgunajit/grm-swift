@props(['compact' => false])
{{-- Three-logo brand strip: ARIAS Society · SWIFT · Govt. of Assam --}}
<div {{ $attributes->merge(['class' => 'flex items-center justify-center gap-4 sm:gap-8']) }}>
    <img src="{{ asset('images/arias-logo.png') }}" alt="ARIAS Society"
         class="{{ $compact ? 'h-8' : 'h-12 sm:h-14' }} w-auto object-contain">
    <img src="{{ asset('images/swift-logo.png') }}" alt="SWIFT Project"
         class="{{ $compact ? 'h-8' : 'h-14 sm:h-16' }} w-auto object-contain">
    <img src="{{ asset('images/assam-govt-logo.png') }}" alt="Government of Assam"
         class="{{ $compact ? 'h-8' : 'h-12 sm:h-14' }} w-auto object-contain">
</div>
