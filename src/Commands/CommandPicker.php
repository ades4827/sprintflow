<?php

namespace Ades4827\Sprintflow\Commands;

use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use function Laravel\Prompts\select;

class CommandPicker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:command-picker';

    /**
     * @var array<int, string>
     */
    protected $aliases = ['cp'];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the project commands and run the selected one';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $projectCommands = collect(Artisan::all())
            ->filter(fn ($command, $name) => $command::class !== self::class && (
                $name === 'optimize:clear'
                || str_starts_with($command::class, 'App\\Console\\Commands\\')
                || str_starts_with($command::class, 'Ades4827\\Sprintflow\\Commands\\')
            ))
            // cache:test needs fakerphp/faker, an optional sprintflow dependency
            ->reject(fn ($command, $name) => $name === 'cache:test' && ! class_exists(Factory::class))
            ->mapWithKeys(fn ($command, $name) => [$name => trim("{$name}  {$command->getDescription()}")])
            ->sortKeys();

        if ($projectCommands->isEmpty()) {
            $this->warn('No project commands found.');

            return self::SUCCESS;
        }

        $selectedCommand = select('Which command do you want to run?', $projectCommands->all(), scroll: 15);

        return $this->call($selectedCommand);
    }
}
