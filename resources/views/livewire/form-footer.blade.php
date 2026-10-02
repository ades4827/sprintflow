<div class="@if(isset($class)) {{ $class }} @else mt-5 @endif">
	@if(\Composer\InstalledVersions::isInstalled('livewire/flux'))
		@include('sprintflow_fluxui::livewire.form-footer')
	@elseif(\Composer\InstalledVersions::isInstalled('wireui/wireui'))
		@include('sprintflow_wireui::livewire.form-footer')
	@endif
</div>
