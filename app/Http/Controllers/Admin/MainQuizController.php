<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreMainQuizRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Quiz;
use App\Models\MainQuiz;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainQuizController extends Controller
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
            $data = MainQuiz::where('event_id',$id)->get();
            return view('admin.main-quiz.index', compact('data','id'));
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
        $data = new MainQuiz();

        $form = [
            'type' => 'create',
            'heading' => 'Create Quiz',
            'method' => 'POST',
            'action' => route('admin.main-quiz.store',$id),
            'cancel_url' => route('admin.main-quiz.index',$id)
        ];

        $events = Event::where('end_date', '>=', date('Y-m-d'))->get();

        return view('admin.main-quiz.form', compact('data', 'form', 'events','id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMainQuizRequest $request,$id)
    {
        try{
            DB::beginTransaction();
            $data = $request->except(
                [
                    '_method',
                    '_token'
                ]
            );

            MainQuiz::create($data);
            DB::commit();
        }catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
        return redirect()
            ->route('admin.main-quiz.index',$id)
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
        $data = MainQuiz::find($id);
        return view('admin.main-quiz.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($ids)
    {
        $data = MainQuiz::find($ids);
        $id = $data->event_id;
        $form = [
            'type' => 'create',
            'heading' => 'Edit Quiz',
            'method' => 'PUT',
            'action' => route('admin.main-quiz.update', $ids),
            'cancel_url' => route('admin.main-quiz.index',$id)
        ];
        $events = Event::where('end_date', '>=', date('Y-m-d'))->get();

        return view('admin.main-quiz.form', compact('data', 'form','id', 'events'));
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

            $quiz = MainQuiz::findOrFail($request->id);

            $data = $request->except(
                [
                    '_method',
                    '_token'
                ]
            );

            $quiz->update($data);

            DB::commit();
            return redirect()
                ->route('admin.main-quiz.index',$quiz->event_id)
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
            $quiz = MainQuiz::find($id);
            return redirect()
                ->route('admin.main-quiz.index')
                ->with('success', 'Quiz has been deleted successfully.');
        }catch (\Exception $exception) {
            return redirect()
                ->back()
                ->with('error', $exception->getMessage());
        }
    }
}
