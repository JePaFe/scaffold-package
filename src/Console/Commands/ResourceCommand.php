<?php

namespace JePaFe\Scaffold\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ResourceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffold:resource {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a migration, factory, views, request, seeder, and resource controller for the model';

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

        $this->call('scaffold:model', ['model' => $model]);
        $this->call('scaffold:migration', ['model' => $model]);
        $this->call('scaffold:factory', ['model' => $model]);
        $this->call('scaffold:seeder', ['model' => $model]);
        $this->call('scaffold:controller', ['model' => $model]);
        $this->call('scaffold:request', ['model' => $model]);

        $this->call('scaffold:view', ['model' => $model, 'view' => 'admin', '--layouts' => true]);
        $this->call('scaffold:view', ['model' => $model, 'view' => 'index']);
        $this->call('scaffold:view', ['model' => $model, 'view' => 'create']);
        $this->call('scaffold:view', ['model' => $model, 'view' => 'edit']);
        $this->call('scaffold:view', ['model' => $model, 'view' => 'show']);
        $this->call('scaffold:view', ['model' => $model, 'view' => '_fields']);
        $this->call('scaffold:view', ['model' => $model, 'view' => '_action']);

        $route = Str::plural(strtolower($model));

        $this->info("
Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    Route::name('admin')->resource('/{$route}', '{$model}Controller');
});
        ");
    }
}
