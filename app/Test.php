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

    public function listQuestions()
	{
		$result = [];
		foreach ($this->questions as $question) {
			$result[] = (object)[
				'id' => $question->id,
				'question' => $question->question,
				'type' => __($question->type),
				'points' => 'Максимальное количество баллов за вопрос: ' . $question->points
			];
		}

		return $result;
	}

	static public function getTypes()
	{
		return [
			['value' => null, 'text' => '...'],
			['value' => 'list', 'text' => __('list')],
			['value' => 'file', 'text' => __('file')],
			['value' => 'text', 'text' => __('text')],
		];
	}
}
