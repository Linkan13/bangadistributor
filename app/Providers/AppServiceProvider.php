<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Laravel\Scout\Console\ImportCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Modules\Language\Entities\Language;
use Modules\GeneralSetting\Entities\GeneralSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('files', function () {
            return new Filesystem;
        });
        $this->app->register(TranslationServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        App::singleton('general_setting', function () {
            return GeneralSetting::first() ?? new GeneralSetting(['favicon' => 'default-favicon.png']);
        });

        App::singleton('current_lang', function () {
            $code = session('locale') ?? config('app.locale', 'en');
            return Language::where('code', $code)->first() ?? new Language(['rtl' => 0]);
        });

        if ($this->app->bound('blade.compiler')) {
            $this->app->afterResolving('blade.compiler', function ($bladeCompiler) {
                $bladeCompiler->directive('datetime', function ($expression) {
                    return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
                });
            });
        }

        // if (!$this->app->runningInConsole()) {
        //     $this->commands([
        //         ImportCommand::class,
        //     ]);
        // }
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate',
                function ($perPage = 15, $page = null, $options = []) {
                $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                return (new LengthAwarePaginator(
                    $this->forPage($page, $perPage), $this->count(), $perPage, $page, $options))
                    ->withPath('');
            });
        }

        if (config('app.force_https')) {
            url()->forceScheme('https');
        }


        Schema::defaultStringLength(191);


        Validator::extend('check_unique_phone', function($attribute, $value, $parameters, $validator) {
            if (is_numeric($value)) {
              $data=User::where('phone',$value)->first();
              if($data){
                return false;
               }
                return true;
            }
            return true;

        });

        Paginator::useBootstrap();

    }
}
