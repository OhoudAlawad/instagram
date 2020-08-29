<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Post;
use App\Like;
use App\Comment;
use App\Follower;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = Post::withCount('likes')->whereIn(
                                             'user_id', auth()->user()->following()->where('accepted','=',1)
                                              ->pluck('to_user_id')
                                                )->orderBy("created_at","DESC")->paginate(9);
        $active_home = "primary";
        return view('home',compact('posts','active_home'));
    }
    /**
     * UserPosts to show the user posts
     *
     */

    public function userPosts(Request $request)
    {
        //
        $posts=Post::where(["user_id"=>auth()->user()->id])->paginate(9);
        $active_profile = "primary";
        return view('post_views/user_posts',compact('posts','active_profile'));
    }

    public function userFriendPosts($id)
    {
        //$is_follower = Follower::where(["from_user_id"=>auth()->user()->id,"to_user_id"=>$id,"accepted"=>1])->get();
        if (policy(Post::Class)->show_friend(auth()->user(),$id)) {
            $posts=Post::withCount('likes')->where(["user_id"=>$id])->paginate(9);
            return view('post_views/frind_posts',compact('posts'));
        }
        else
            return redirect('home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Load the new _post view
        return view('post_views/new_post');


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //save the post
        if($request->hasfile('filename'))
        {
            $file = $request->file('filename');
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/images/', $name);
        }
        $Post= new Post();
        $Post->body=$request->get('body');
        $Post->user_id=auth()->user()->id;
        $Post->image_path=$name;
        $Post->save();

        return redirect('post/'.$Post->id);
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
        $post = Post::with('user')->find($id);
        $user = auth()->user();
        if ($user->can('show', $post)) {
            $count = Like::where('post_id', $id)->count();
            $userLike = Like::where(["user_id" => auth()->user()->id, "post_id" => $id])->get();
            $post_comments = Post::with('comments', 'comments.user')->find($id);
            return view('post_views/view_post', compact('post', 'count', 'userLike', 'post_comments'));
        }
        else
            return redirect('not_found');
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
        $post = Post::find($id);
        if (auth()->user()->can('edit', $post)) {
            return view('post_views/edit_post', compact('post'));
        }
        else
            return redirect('not_found');

        }


        /**
         * Update the specified resource in storage.
         *
         * @param \Illuminate\Http\Request $request
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
            //
            $post = Post::find($id);
            if (auth()->user()->can('update', $post)) {
                $post->body = $request->get('body');
                $post->save();
                return redirect('post/' .$id);
            }
            else
                return redirect('not_found');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         * @return \Illuminate\Http\Response
         */
        public
        function destroy($id)
        {
            //
            $post = Post::find($id);
            if (auth()->user()->can('delete', $post)) {
                $post->delete();
                return redirect('user/posts')->with('success', 'Post has been deleted');
            } else
                return redirect('not_found');

            }
        }


