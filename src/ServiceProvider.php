<?php namespace Stolz\LaravelFormBuilder;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Html\HtmlBuilder;
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
		// Bind 'html' shared component to the IoC container
		$this->app->singleton('html', function ($app) {

			return new HtmlBuilder($app['url']);
		});

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
		$loader = AliasLoader::getInstance();

		// Add 'Form' and 'Html' facade aliases
		$loader->alias('Form', 'Illuminate\Html\FormFacade');
		$loader->alias('Html', 'Illuminate\Html\HtmlFacade');
	}
}
