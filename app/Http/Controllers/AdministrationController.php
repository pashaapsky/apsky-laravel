<?php

namespace App\Http\Controllers;

use App\News;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdministrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index() {
        return view('admin.index');
    }

    public function posts() {
        $posts = Cache::tags(['posts', 'tags', 'comments'])->remember('posts', 3600, function () {
            return Post::with(['tags', 'comments'])->latest()->get();
        });

        return view('/admin.posts', compact('posts'));
    }

    public function news() {
        $news = Cache::tags(['news', 'tags', 'comments'])->remember('news', 3600, function () {
            return News::with(['tags', 'comments'])->latest()->get();
        });

        return view('/admin.news', compact('news'));
    }

    public function orders() {
        return view('/admin.orders');
    }

    public function ordersStore(Request $request) {

        \App\Jobs\GenerateResultingOrder::dispatch($request->all(), auth()->user());

        return back();
    }
}
