<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Event;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Admin Dashboard
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if(auth()->user()->type == 2) {
            return redirect()->route('admin.event.index');
        } elseif(auth()->user()->type == 1) {
            return redirect()->route('admin.notification.index');
        }
        
        $usersCount = User::count();
        $usersActiveCount = User::where('is_active',1)->count();
        $blogsCount = Blog::count();
        $eventsCount = Event::count();
        $upcomingCount = Event::where('date','>',date('Y-m-d'))->count();
        $testimonialsCount = Testimonial::count();
        $pageCount = Page::count();
        $users = User::orderBy('id', 'DESC')->take(5)->get();
        return view('admin.dashboard.index', compact(
            'usersCount',
            'blogsCount',
            'testimonialsCount',
            'pageCount',
            'users',
            'eventsCount',
            'usersActiveCount',
            'upcomingCount'
        ));
    }
}
