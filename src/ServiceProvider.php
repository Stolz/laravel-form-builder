<?php namespace Stolz\LaravelFormBuilder;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
	/**
	 * Register bindings in the container.
	 *
	 * @return void
	 */
	public function register()
	{
		// Bind 'form' shared component to the IoC container
		$this->app->singleton('form', function ($app) {

			$form = new FormBuilder(
				$app['html'],
				$app['url'],
				$app['session.store']->getToken(),
				$app['translator'],
				$app['session.store']->get('errors')
			);

			return $form->setSessionStore($app['session.store']);
		});
	}

	/**
	 * Perform post-registration booting of services.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Add 'Form' facade alias
		AliasLoader::getInstance()->alias('Form', 'Illuminate\Html\FormFacade');
	}
}
