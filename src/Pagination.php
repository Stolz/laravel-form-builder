<?php namespace Stolz\LaravelFormBuilder;

use Illuminate\Pagination\BootstrapThreePresenter;

class Pagination extends BootstrapThreePresenter
{
	/**
	 * Whether or not pagination will be render centered.
	 *
	 * @var boolean
	 */
	protected $centered = true;

	/**
	 * Shortcut alias for $this->render()
	 *
	 * @return string
	 */
	public function __tostring()
	{
		return (string) $this->render();
	}

	/**
	 * Set centered attribute
	 *
	 * @param  bool
	 * @return $this
	 */
	public function centered($centered)
	{
		$this->centered = (bool) $centered;

		return $this;
	}

	/**
	 * Convert the URL window into Zurb Foundation HTML.
	 *
	 * @return string
	 */
	public function render()
	{
		if( ! $this->hasPages())
			return '';

		$html = sprintf(
			'<ul class="pagination" aria-label="Pagination">%s %s %s</ul>',
			$this->getPreviousButton(),
			$this->getLinks(),
			$this->getNextButton()
		);

		return ($this->centered) ? '<div class="pagination-centered">' . $html . '</div>' : $html;
	}

	/**
	 * Get HTML wrapper for disabled text.
	 *
	 * @param  string  $text
	 * @return string
	 */
	protected function getDisabledTextWrapper($text)
	{
		return '<li class="unavailable" aria-disabled="true"><a href="javascript:void(0)">'.$text.'</a></li>';
	}

	/**
	 * Get HTML wrapper for active text.
	 *
	 * @param  string  $text
	 * @return string
	 */
	protected function getActivePageWrapper($text)
	{
		return '<li class="current"><a href="javascript:void(0)">'.$text.'</a></li>';
	}

	/**
	 * Get a pagination "dot" element.
	 *
	 * @return string
	 */
	protected function getDots()
	{
		return $this->getDisabledTextWrapper('&hellip;');
	}
}
