<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * タスク一覧取得
     *
     * @return array
     */
    public function index()
    {
        $tasks = Auth::user()
            ->tasks()
            ->orderByDesc('created_at')
            ->get();

        return ['data' => $tasks];
    }

    /**
     * タスク登録
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
        ]);

        Auth::user()->tasks()->create([
            'content' => $request->get('content'),
        ]);

        return ['message' => 'Success'];
    }

    /**
     * タスク1件取得
     *
     * @param  int $id
     * @return array
     */
    public function show($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);

        return ['data' => $task];
    }

    /**
     * タスク更新
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
        ]);

        $task = Auth::user()->tasks()->findOrFail($id);
        $task->fill([
            'content' => $request->get('content'),
        ]);
        $task->save();

        return ['message' => 'Success'];
    }

    /**
     * タスク削除
     *
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function destroy($id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $task->delete();

        return ['message' => 'Success'];
    }
}
