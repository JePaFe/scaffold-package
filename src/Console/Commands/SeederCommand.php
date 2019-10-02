<?php

namespace JePaFe\Scaffold\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class SeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:seeder {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new seeder class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = ucfirst(basename($this->argument('model')));

        $stub = __DIR__ . '/stubs/Seeder.stub';
        $stub_file = $this->files->get($stub);
        $model_plural = Str::plural($model);
        $stub = str_replace('{{class}}', "{$model_plural}TableSeeder", $stub_file);

        $seeds_path = database_path('seeds');

        $seeder_path = "{$seeds_path}/{$model_plural}TableSeeder.php";

        if ($this->files->exists($seeder_path)) {
            $this->error('Seeder already exists!');
        } else {
            $this->files->put($seeder_path, $stub);

            $this->info('Seeder created successfully.');
        }
    }
}
