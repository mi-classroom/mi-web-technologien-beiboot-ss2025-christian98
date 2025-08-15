<?php

namespace App\Providers;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
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
        //
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
}
