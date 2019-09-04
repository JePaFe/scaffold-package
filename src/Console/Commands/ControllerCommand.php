<?php

namespace JePaFe\Scaffold\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:controller {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource controller class';

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

        $stub = __DIR__ . '/stubs/Controller.stub';
        $stub_file = $this->files->get($stub);

        $singular = strtolower($model);
        $search = ['{{model}}', '{{singular}}'];
        $replace = [$model, $singular];

        $stub = str_replace($search, $replace, $stub_file);

        $controllers_path = app_path('Http/Controllers/Admin');

        if (!$this->files->exists($controllers_path)) {
            $this->files->makeDirectory($controllers_path, 0755);
        }

        $controller_path = "{$controllers_path}/{$model}Controller.php";

        if ($this->files->exists($controller_path)) {
            $this->error('Controller already exists!');
        } else {
            $this->files->put($controller_path, $stub);

            $this->info('Controller created successfully.');
        }
    }
}
