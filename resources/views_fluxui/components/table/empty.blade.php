@props(['message' => 'Nessun risultato trovato'])
<flux:table.row key="no_result" class="bg-zinc-50">
	<flux:table.cell colspan="100" align="center" class="py-8">
		{{ $message }}
	</flux:table.cell>
</flux:table.row>