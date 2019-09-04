<?php

namespace JePaFe\Scaffold\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:model {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class';

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

        $stub = __DIR__ . '/stubs/Model.stub';
        $stub_file = $this->files->get($stub);

        $search = ['{{class}}', '{{plural}}'];
        $replace = [$model, Str::plural(strtolower($model))];
        $stub = str_replace($search, $replace, $stub_file);

        $models_path = base_path('app/Models');

        if (!$this->files->exists($models_path)) {
            $this->files->makeDirectory($models_path, 0755);
        }

        $model_path = "$models_path/$model.php";

        if ($this->files->exists($model_path)) {
            $this->error('Model already exists!');
        } else {
            $this->files->put($model_path, $stub);

            $this->info('Model created successfully.');
        }
    }
}
