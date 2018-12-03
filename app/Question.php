<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

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


    public function test()
    {
        return $this->belongsTo('App\Test');
    }
}
