<?php
namespace mnshankar\RoleBasedAuthority;

use Illuminate\Support\ServiceProvider;

class RoleBasedAuthorityServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->package('mnshankar/role-based-authority');
        
        $this->app['authority'] = $this->app->share(function ($app)
        {
            $cache = $app['config']->get('role-based-authority::cache', false);
            $cache_ttl = $app['config']->get('role-based-authority::cache_ttl', 0);
            $cacheObj = $app->make('cache');
            if ($cache && $cacheObj->has('role-based-authority'))
            {
                return $cacheObj->get('role-based-authority');
            }
            $user = $app['auth']->user();
            $authority = new \Authority\Authority($user);
            $fn = $app['config']->get('role-based-authority::initialize', null);
            
            if ($fn) {
                $fn($authority);
            }
            if ($cache)
            {
                $cacheObj->put('role-based-authority', $authority, $cache_ttl);
            }            
            return $authority;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'authority'
        );
    }
}
