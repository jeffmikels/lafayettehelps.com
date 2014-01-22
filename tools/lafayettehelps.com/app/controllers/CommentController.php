<?php

class CommentController extends BaseController
{
	public function add()
	{
		$comment = new Comment;
		if (! Auth::check()) error('You must be logged in to leave a comment');
		if (! Input::has('_token')) error('Your form did not have a valid token');
		$commentable_type = Input::get('commentable_type');
		$commentable_id = Input::get('commentable_id');
		$redirect_after = $commentable_type . '/' . $commentable_id;

		$input = Input::all();
		$input['user_id'] = Auth::user()->id;

		$comment->updateFromArray( $input );
		return Redirect::to($redirect_after);
	}
}
