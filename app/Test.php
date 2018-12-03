<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
	protected $table = 'tests';

	/**
	 * Get settings object
	 *
	 * @param  string  $value
	 * @return string
	 */
	public function getSettingsAttribute($value)
	{
		return json_decode($value);
	}
	/**
	 * Set settings value
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setSettingsAttribute($value)
	{
		$this->attributes['settings'] = json_encode($value);
	}

    public function questions()
    {
        return $this->hasMany('App\Question');
    }
}
