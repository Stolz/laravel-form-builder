<?php namespace Stolz\LaravelFormBuilder;

class ServiceProvider extends \Stevenmaguire\Foundation\FoundationServiceProvider
{
	/**
	 * Register the form builder instance.
	 *
	 * @return void
	 */
	protected function registerFormBuilder()
	{
		$this->app['form'] = $this->app->share(function ($app) {

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
}
