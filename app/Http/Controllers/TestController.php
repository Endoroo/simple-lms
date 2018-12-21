<?php

namespace App\Http\Controllers;

use App\Test;
use Illuminate\Http\Request;
use Validator;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($unpack = false)
    {
    	$tests = Test::all();
		$items = [];
		foreach ($tests as $test) {
			$items[] = [
				'id' => $test->id,
				'name' => $test->name,
				'actions' => true
			];
		}
		$result = [
            'tests' => $items,
            'questionTypes' => Test::getTypes(),
            'title' => __('Тесты'),
            'add' => __('Добавить тест'),
            'fields' =>  [
                'id' => [ 'label' => '№'],
                'name' => [ 'label' => __('Заголовок')],
                'actions' => [ 'label' => __('Действия')],
            ]
        ];
        return !$unpack ? response()->view('tests.index', $result) : $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return response()->view('tests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$data = $request->all();
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
			'settings.time' => 'required|regex:/\d{2}:\d{2}/',
			'settings.percent' => 'required|integer',
			'settings.retake' => 'required|integer'
		]);

		if ($validator->fails()) {
			return redirect('tests/create')
				->withErrors($validator)
				->withInput($data);
		}
		if (!isset($data['settings']['options'])) {
			$data['settings']['options'] = [];
		}

		$test = new Test;
		$test->name = $data['name'];
		$test->settings = $data['settings'];
		$test->save();

		return redirect('tests')->with('message', 'Тест добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* @var $test Test */
        if ($test = Test::find($id)) {
            return response()->view('tests.edit', [
            	'test' => $test->toArray(),
				'questions' => $test->listQuestions(),
                'questionTypes' => Test::getTypes(),
			]);
        }
        return response()->view('errors::403', ['exception' => new \Exception(__('Access denied'))]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'settings.time' => 'required|regex:/\d{2}:\d{2}/',
            'settings.percent' => 'required|integer',
            'settings.retake' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect('tests/create')
                ->withErrors($validator)
                ->withInput($data);
        }
        if (!isset($data['settings']['options'])) {
            $data['settings']['options'] = [];
        }

        if ($test = Test::find($id)) {
            $test->name = $data['name'];
            $test->settings = $data['settings'];
            $test->save();
            return redirect('tests/' . $id . '/edit')->with('message', 'Тест отредактирован');
        }
        return response()->view('errors::403', ['exception' => new \Exception(__('Access denied'))]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$test = Test::find($id);
    	$test->delete();
        return response()->json($this->index(1));
    }

    public function run($id)
	{
		$state = session('test');
		if ($test = Test::find($id)) {
			$result = false;
			if (!$state) {
				$questions = $test->questions;
				foreach ($test->settings->options as $option) {
					if ($option == 'random_order') {
						/* @var $questions \Illuminate\Support\Collection */
						$questions = $questions->shuffle();
					}
				}
				$prepare = [];
				$number = 0;
				foreach ($questions as $question) {
					$prepare[$question->id] = (object) [
						'id' => $question->id,
						'number' => ++$number,
						'type' => $question->type,
						'settings' => $question->settings,
						'question' => $question->question
					];
				}
				$questions = collect($prepare);
				$result = (object) [
					'id' => $test->id,
					'questions' => $questions,
					'beginning' => date('Y-m-d H:i:s'),
					'time' => $test->settings->time,
					'remain_time' => $test->settings->time,
					'allow_skip' => in_array('allow_skip', $test->settings->options),
					'allow_back' => in_array('allow_back', $test->settings->options),
					'current_question' => $questions->first()->id,
					'answers' => []
				];
				session(['test' => $result]);
			}
			else {
				$time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $state->time);
				sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);
				$seconds = $hours * 60 * 60 + $minutes * 60 + $seconds;
				$time = time() - strtotime($state->beginning);
				if ($time <= $seconds) {
					$remain = $seconds - $time;
					$hours = floor($remain / 60 / 60);
					$hours = $hours < 10 ? '0' . $hours : $hours;
					$minutes = floor(($remain - $hours * 60 * 60) / 60);
					$minutes = $minutes < 10 ? '0' . $minutes : $minutes;
					$seconds = $remain - $hours * 60 * 60 - $minutes * 60;
					$seconds = $seconds < 10 ? '0' . $seconds : $seconds;

					$state->remain_time = implode(':' , [$hours, $minutes, $seconds]);
					session(['test' => $state]);
					$result = $state;
				} else {
					session()->forget('test');
				}
			}

			return response()->view('tests.run', [
				'test' => $result
			]);
		}

		return response()->view('errors::403', ['exception' => new \Exception(__('Access denied'))]);
	}
}
