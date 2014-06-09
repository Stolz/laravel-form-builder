<?php namespace Stolz\Foundation;

class FormBuilder extends \Stevenmaguire\Foundation\FormBuilder
{
	/**
	 * Get the check state for a checkbox input.
	 *
	 * @param  string  $name
	 * @param  mixed  $value
	 * @param  bool  $checked
	 * @return bool
	 */
	protected function getCheckboxCheckedState ($name, $value, $checked)
	{
		if (isset($this->session) && ! $this->oldInputIsEmpty() && is_null($this->old($name))) return false;

		if ($this->missingOldAndModel($name)) return $checked;

		$posted = $this->getValueAttribute($name);

		// Fix for Laravel BUG #2548 https://github.com/laravel/framework/issues/2548
		if($posted instanceof \Illuminate\Database\Eloquent\Collection)
			return $posted->contains($value);

		return is_array($posted) ? in_array($value, $posted) : (bool) $posted;
	}
}
