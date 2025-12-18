<button
    @click="activeTab = '{{ $index }}'"
    :class="activeTab === '{{ $index }}' ? 'selected' : 'not-selected'"
    type="button" {{ $attributes }}>
    {{ $slot }}
</button>
