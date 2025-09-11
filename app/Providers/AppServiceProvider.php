<?php

namespace App\Providers;

use App\Services\Session\Toast\Toast;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerMacros();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::extend('webdav', function (Application $app, array $config) {
            $pathPrefix = $config['pathPrefix'] ?? null;
            $client = new Client($config);
            $adapter = new WebDAVAdapter($client, $pathPrefix);

            return new FilesystemAdapter(new Filesystem($adapter, $config), $adapter, $config);
        });

        Storage::extend('dropbox', function (Application $app, array $config) {
            $adapter = new DropboxAdapter(
                new DropboxClient($config['accessToken'])
            );

            return new FilesystemAdapter(new Filesystem($adapter, $config), $adapter, $config);
        });
    }

    public function registerMacros(): void
    {
        RedirectResponse::macro(
            'withSuccess',
            function (string $message) {
                Toast::success($message)->flash();

                return $this;
            }
        );

        RedirectResponse::macro(
            'withError',
            function (string $message) {
                Toast::error($message)->flash();

                return $this;
            }
        );
    }
}
