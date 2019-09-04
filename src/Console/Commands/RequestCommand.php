<?php

namespace JePaFe\Scaffold\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class RequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:request {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class';

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

        $stub = __DIR__ . '/stubs/Request.stub';
        $stub_file = $this->files->get($stub);
        $stub = str_replace('{{class}}', $model, $stub_file);

        $requests_path = app_path('Http/Requests');

        if (!$this->files->exists($requests_path)) {
            $this->files->makeDirectory($requests_path, 0755);
        }

        $request_path = "$requests_path/$model.php";

        if ($this->files->exists($request_path)) {
            $this->error('Request already exists!');
        } else {
            $this->files->put($request_path, $stub);

            $this->info('Request created successfully.');
        }
    }
}
