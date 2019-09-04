<?php

namespace JePaFe\Scaffold\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class FactoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:factory {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model factory';

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

        $stub = __DIR__ . '/stubs/Factory.stub';
        $stub_file = $this->files->get($stub);
        $stub = str_replace('{{model}}', $model, $stub_file);

        $factories_path = database_path('factories');

        $factory_path = "{$factories_path}/{$model}Factory.php";

        if ($this->files->exists($factory_path)) {
            $this->error('Factory already exists!');
        } else {
            $this->files->put($factory_path, $stub);

            $this->info('Factory created successfully.');
        }
    }
}
