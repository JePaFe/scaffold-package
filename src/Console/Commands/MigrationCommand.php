<?php

namespace JePaFe\Scaffold\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:migration {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file';

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

        $stub = __DIR__ . DIRECTORY_SEPARATOR . 'stubs/Migration.stub';
        $stub_file = $this->files->get($stub);

        $search = ['{{class}}', '{{table}}'];
        $replace = ['Create' . Str::plural($model) . 'Table', Str::plural(Str::snake($model))];
        $stub = str_replace($search, $replace, $stub_file);

        $model_plural = Str::plural(strtolower($model));

        $migrations_path = database_path('migrations');
        $date = date('Y_m_d_His');
        $migration_path = "{$migrations_path}/{$date}_create_{$model_plural}_table.php";

        if (!empty(glob("{$migrations_path}/*_create_{$model_plural}_table.php"))){
            $this->error('Migration already exists!');
        } else {
            $this->files->put($migration_path, $stub);

            $this->info('Migration created successfully.');
        }
    }
}
