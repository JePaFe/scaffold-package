<?php

namespace JePaFe\Scaffold\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:view {model} {view} {--layouts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view file';

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
        $view = $this->argument('view');

        $layouts = '';

        if ($this->option('layouts')) {
            $layouts = 'layouts/';
        }

        $stub = __DIR__ . "/stubs/views/{$layouts}{$view}.stub";

        $stub_file = $this->files->get($stub);

        $singular = strtolower($model);
        $search = ['{{model}}', '{{plural}}', '{{singular}}', '{{model_plural}}'];
        $replace = [$model, Str::plural(strtolower($model)), $singular, Str::plural($model)];
        $stub = str_replace($search, $replace, $stub_file);

        $views_path = resource_path('views');

        if ($this->option('layouts')) {
            $view_path = "{$views_path}/layouts";
        } else {
            $view_path = "{$views_path}/admin/{$singular}";
        }

        if (!$this->files->exists($view_path)) {
            $this->files->makeDirectory($view_path, 0755, true);
        }

        $view_path .= "/{$view}.blade.php";

        if ($this->files->exists($view_path)) {
            $this->error('View already exists!');
        } else {
            $this->files->put($view_path, $stub);

            $this->info('View created successfully.');
        }
    }
}
