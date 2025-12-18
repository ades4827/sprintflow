<div class="sf-tabs" x-data="{ activeTab: '{{ $active }}' }">
    <div class="sf-tabs-header">
        <nav aria-label="Tabs">
            {{ $headers }}
        </nav>
    </div>
    <div class="sf-tabs-tabs">
        {{ $slot }}
    </div>
</div>
