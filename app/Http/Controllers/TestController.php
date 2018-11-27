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
    public function index()
    {
        return response()->view('tests.index', Test::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		return response()->view('tests.form');
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
			'settings.retake' => 'required|integer',
			'settings.options' => 'array|present',
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
        //
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
        //
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
        return $this->frontend();
    }

    public function frontend()
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

		return response()->json([
			'title' => __('Тесты'),
			'add' => __('Добавить тест'),
			'fields' =>  [
				'id' => [ 'label' => '№'],
				'name' => [ 'label' => __('Заголовок')],
				'actions' => [ 'label' => __('Действия')],
			],
			'items' =>  $items
		]);
	}
}
