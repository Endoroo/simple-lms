<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Request $request)
    {
        $request = $request->validate([
            'test_id' => 'required|int',
            'question' => 'required|string',
            'points' => 'required|numeric',
            'type' => 'required|string',
            'settings' => 'present|array'
        ]);

        $question = new Question;
        $question->test_id = $request['test_id'];
        $question->question = $request['question'];
        $question->points = $request['points'];
        $question->type = $request['type'];
        $question->settings = $request['settings'];
        $question->save();

        return response()->json(['success' => true, 'message' => 'Добавление прошло успешно']);
    }
}
