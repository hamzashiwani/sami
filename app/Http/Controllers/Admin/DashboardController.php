<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Testimonial;
use App\Models\User;

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
        $usersCount = User::count();
        $blogsCount = Blog::count();
        $testimonialsCount = Testimonial::count();
        $pageCount = Page::count();
        $users = User::orderBy('id', 'DESC')->take(5)->get();
        return view('admin.dashboard.index', compact(
            'usersCount',
            'blogsCount',
            'testimonialsCount',
            'pageCount',
            'users'
        ));
    }
}
