@props([
    'hover' => false,
    'gradient' => false,
    'gradientDirection' => 'bg-gradient-to-br',
    'gradientFrom' => 'from-primary-500/10',
    'gradientTo' => 'to-secondary-500/10',
    'border' => true,
])

<div {{ $attributes->merge([
    'class' => 'rounded-xl transition-all duration-300 p-6 ' .
        ($hover ? 'transform hover:scale-[1.02] hover:shadow-lg ' : '') .
        ($gradient ? "$gradientDirection $gradientFrom $gradientTo " : 'bg-white dark:bg-gray-800 ') .
        ($border ? 'border border-gray-200 dark:border-gray-700 ' : '') .
        'shadow-md'
]) }}>
    {{ $slot }}
</div>