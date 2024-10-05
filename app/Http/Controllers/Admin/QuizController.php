<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Quiz;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    private $quiz;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Quiz $quiz)
    {
        $this->middleware('auth:admin');
        $this->quiz = $quiz;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        try{
            $data = Quiz::where('quiz_id',$id)->get();
            return view('admin.quiz.index', compact('data','id'));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data = new Quiz();

        $form = [
            'type' => 'create',
            'heading' => 'Create Quiz',
            'method' => 'POST',
            'action' => route('admin.quiz.store',$id),
            'cancel_url' => route('admin.quiz.index',$id)
        ];

        $events = Event::where('end_date', '>=', date('Y-m-d'))->get();

        return view('admin.quiz.form', compact('data', 'form', 'events','id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        try{
            DB::beginTransaction();
            $data = $request->except(
                [
                    '_method',
                    '_token'
                ]
            );

            Quiz::create($data);
            DB::commit();
        }catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
        return redirect()
            ->route('admin.quiz.index',$id)
            ->with('success', 'Quiz has been added successfully.');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Quiz::find($id);
        return view('admin.quiz.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($ids)
    {
        $data = Quiz::find($ids);
        $id = $data->quiz_id;
        $form = [
            'type' => 'create',
            'heading' => 'Edit Quiz',
            'method' => 'PUT',
            'action' => route('admin.quiz.update', $ids),
            'cancel_url' => route('admin.quiz.index',$id)
        ];
        $events = Event::where('end_date', '>=', date('Y-m-d'))->get();

        return view('admin.quiz.form', compact('data', 'form','id', 'events'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $quiz = Quiz::findOrFail($request->id);

            $data = $request->except(
                [
                    '_method',
                    '_token'
                ]
            );

            $quiz->update($data);

            DB::commit();
            return redirect()
                ->route('admin.quiz.index',$quiz->quiz_id)
                ->with('success', 'Quiz has been updated successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaFile  $mediaFile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $quiz = Quiz::find($id);
            return redirect()
                ->route('admin.quiz.index')
                ->with('success', 'Quiz has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
}
