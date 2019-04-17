<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Session\Store;

class PostController extends Controller
{
    public function getIndex(Store $session)
    {
        $post = new Post();
        $posts = Post::orderBy('created_at','desc')->get();
        return view('blog.index', ['posts' => $posts]);
    }

    public function getAdminIndex(Store $session)
    {
        $post = new Post();
        $posts =Post::orderBy('title','asc')->get();
        return view('admin.index', ['posts' => $posts]);
    }

    public function getPost(Store $session, $id)
    {
        $post = new Post();
        $post = Post::where('id',$id)->first();
        return view('blog.post', ['post' => $post]);
    }

    public function getAdminCreate()
    {
        return view('admin.create');
    }

    public function getAdminEdit(Store $session, $id)
    {
        $post = new Post();
        $post = Post::find($id);
        return view('admin.edit', ['post' => $post, 'postId' => $id]);
    }

    public function postAdminCreate(Store $session, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post = new Post([
            'title'=>$request->input('title'),
            'content'=>$request->input('content')
        ]);
        $post->save();
        return redirect()->route('admin.index')->with('info', 'Post created, Title is: ' . $request->input('title'));
    }

    public function postAdminUpdate(Store $session, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post=Post::find($request->input('id'));
        $post->title=$request->input('title');
        $post->content=$request->input('content');
        $post->save();
        return redirect()->route('admin.index')->with('info', 'Post edited, new Title is: ' . $request->input('title'));
    }
    public function getAdminDelete($id)
    {
        $post=Post::find($id);
        $post->delete();
        return redirect()->route('admin.index')->with('info','Post deleted!');
    }
}
