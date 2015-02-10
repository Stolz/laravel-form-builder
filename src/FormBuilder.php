<?php namespace Stolz\LaravelFormBuilder;

class FormBuilder extends Foundation
{
	/**
	 * Get the check state for a checkbox input.
	 *
	 * @param  string  $name
	 * @param  mixed   $value
	 * @param  bool    $checked
	 * @return bool
	 */
	protected function getCheckboxCheckedState($name, $value, $checked)
	{
		if (isset($this->session) && ! $this->oldInputIsEmpty() && is_null($this->old($name))) return false;

		if ($this->missingOldAndModel($name)) return $checked;

		$posted = $this->getValueAttribute($name);

		// Fix for Laravel BUG #2548 https://github.com/laravel/framework/issues/2548
		if($posted instanceof \Illuminate\Database\Eloquent\Collection)
			return $posted->contains($value);

		return is_array($posted) ? in_array($value, $posted) : (bool) $posted;
	}

	/**
	 * Get the check state for a radio input.
	 *
	 * @param  string  $name
	 * @param  mixed  $value
	 * @param  bool  $checked
	 * @return bool
	 */
	protected function getRadioCheckedState($name, $value, $checked)
	{
		if ($this->missingOldAndModel($name)) return $checked;

		$posted = $this->getValueAttribute($name);

		// Fix for Laravel BUG #2548 https://github.com/laravel/framework/issues/2548
		if($posted instanceof \Illuminate\Database\Eloquent\Collection)
			return $posted->contains($value);

		return ($posted == $value);
	}

	/**
	 * Create a group of checkboxes.
	 *
	 * @param  string  $name
	 * @param  array   $values
	 * @param  array   $checked
	 * @param  array   $options
	 * @return string
	 */
	public function checkboxes($name, $values, $checked = array(), $options = array())
	{
		return $this->checkables('checkbox', $name, $values, $checked, $options);
	}

	/**
	 * Create a group of radio buttons.
	 *
	 * @param  string  $name
	 * @param  array   $values
	 * @param  array   $checked
	 * @param  array   $options
	 * @return string
	 */
	public function radios($name, $values, $checked = array(), $options = array())
	{
		return $this->checkables('radio', $name, $values, $checked, $options);
	}

	/**
	 * Create a group of checkable input fields.
	 *
	 * $options[
	 *   'legend' => 'txt', // to get a fieldset with legend
	 *   'small'  => 1,     // Number of grid columns for small screens
	 *   'medium' => 2,     // Number of grid columns for medium screens
	 *   'large'  => 3,     // Number of grid columns for large screens
	 * ]
	 *
	 * @param  string  $type
	 * @param  string  $name
	 * @param  array   $values
	 * @param  array   $checked
	 * @param  array   $options
	 * @return string
	 */
	public function checkables($type, $name, $values, $checked, $options)
	{
		// Unset options that should not to be passed to $this->*
		$legend = (isset($options['legend'])) ? $options['legend'] : false;
		$small = (isset($options['small'])) ? $options['small'] : 2;
		$medium = (isset($options['medium'])) ? $options['medium'] : 3;
		$large = (isset($options['large'])) ? $options['large'] : 4;
		unset($options['legend'], $options['small'], $options['medium'], $options['large']);

		$out = ($legend) ? '<fieldset class="checkables"><legend>' . $legend . '</legend>' : null;
		if($legend and $type === 'checkbox')
			$out .= '
			<div class="checkbox_togglers">
				<a href="all">'._('all').'</a> &#8226;
				<a href="none">'._('none').'</a> &#8226;
				<a href="invert">'._('invert').'</a>
			</div>';

		$out .= "<ul class=\"checkables small-block-grid-$small medium-block-grid-$medium large-block-grid-$large\">";

		foreach($values as $value => $label)
		{
			$options['id'] = $id = $name . $value;
			$check = in_array($value, $checked);

			$out .= '<li><label for="' . $id . '" style="display:inline">';
			$out .= ($type === 'radio') ? $this->radio($name, $value, $check, $options) : $this->checkbox($name.'[]', $value, $check, $options);
			$out .= '&nbsp;'.$label;
			$out .= '</label></li>';
		}

		$out .= '</ul>';

		if($legend)
			$out .= '</fieldset>';

		return $out;
	}
}
