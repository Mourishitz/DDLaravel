<?php

namespace App\Core\Scripts;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Pluralizer;

class ModuleBuilder
{
    public string $modulePath;

    public string $moduleName;

    public function __construct()
    {
    }

    /**
     * @param  mixed  $module
     */
    public function build($module): self
    {
        $this->moduleName = $module;

        return $this;
    }

    /**
     * @param  mixed  $path
     */
    public function onPath($path): self
    {
        $this->modulePath = $path;

        return $this;
    }

    private function createSubModule(string $submodule): string
    {
        $path = $this->modulePath.'/'.$submodule;

        if (File::exists($path)) {
            return $path;
        }

        File::makeDirectory($path);

        return $path;
    }

    private function readFromGist(string $gist): string
    {
        $file = file_get_contents($gist);

        return str_replace('{{ ModuleName }}', $this->moduleName, $file);
    }

    public function controller(): self
    {
        $path = $this->createSubModule('Controllers');
        $file = $this->readFromGist(env('GIST_CONTROLLER_STUB'));
        File::put($path.'/'.$this->moduleName.'Controller.php', $file);

        return $this;
    }

    public function repository(): self
    {
        $path = $this->createSubModule('Repositories');
        $file = $this->readFromGist(env('GIST_REPOSITORY_STUB'));
        File::put($path.'/'.$this->moduleName.'Repository.php', $file);

        return $this;
    }

    public function service(): self
    {
        $path = $this->createSubModule('Services');
        $file = $this->readFromGist(env('GIST_SERVICE_STUB'));
        File::put($path.'/'.$this->moduleName.'Service.php', $file);

        return $this;
    }

    public function model(): self
    {
        $path = $this->createSubModule('Models');
        $file = $this->readFromGist(env('GIST_MODEL_STUB'));

        File::put($path.'/'.$this->moduleName.'Model.php', $file);

        return $this;
    }

    public function migration(): self
    {
        $path = $this->createSubModule('Migrations');
        $file = $this->readFromGist(env('GIST_MIGRATION_STUB'));
        File::put($path.'/'.date('Y_m_d_His').'_create_'.strtolower(Pluralizer::plural($this->moduleName)).'_table.php', $file);

        return $this;
    }

    public function factory(): self
    {
        $path = $this->createSubModule('Factories');
        $file = $this->readFromGist(env('GIST_FACTORY_STUB'));
        File::put($path.'/'.$this->moduleName.'Factory.php', $file);

        return $this;
    }

    public function seeder(): self
    {
        $path = $this->createSubModule('Seeders');
        $file = $this->readFromGist(env('GIST_SEEDER_STUB'));
        File::put($path.'/'.$this->moduleName.'Seeder.php', $file);

        return $this;
    }

    public function request(): self
    {
        $path = $this->createSubModule('Requests');
        $file = $this->readFromGist(env('GIST_REQUEST_STUB'));
        File::put($path.'/'.$this->moduleName.'Request.php', $file);

        return $this;
    }

    public function resource(): self
    {
        $path = $this->createSubModule('Resources');
        $file = $this->readFromGist(env('GIST_RESOURCE_STUB'));
        File::put($path.'/'.$this->moduleName.'Resource.php', $file);

        return $this;
    }

    public function test(): self
    {
        $path = $this->createSubModule('Tests');
        $file = $this->readFromGist(env('GIST_TEST_STUB'));
        File::put($path.'/'.$this->moduleName.'Test.php', $file);

        return $this;
    }

    public function router(): self
    {
        $path = $this->createSubModule('Routers');
        $file = $this->readFromGist(env('GIST_ROUTER_STUB'));
        $file = str_replace('{{ RouterPrefix }}', strtolower($this->moduleName), $file);
        File::put($path.'/'.$this->moduleName.'Router.php', $file);

        return $this;
    }

    /**
     * @param  array<int,mixed>  $for
     * @return $this
     */
    public function withInterfaces(array $for): self
    {
        $path = $this->createSubModule('Interfaces');
        Arr::map($for, function (string $module) use ($path) {
            $file = $this->readFromGist(env('GIST_'.strtoupper($module).'_INTERFACE_STUB'));
            File::put($path.'/'.$this->moduleName.ucfirst($module).'Interface.php', $file);
        });

        return $this;
    }
}
