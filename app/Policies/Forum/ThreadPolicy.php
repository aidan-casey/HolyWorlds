<?php namespace App\Policies\Forum;

use Gate;
use App\Models\Thread;

class ThreadPolicy extends \App\Policies\ThreadPolicy
{
    /**
     * Permission: Delete posts in thread.
     *
     * @param  object  $user
     * @param  Thread  $thread
     * @return bool
     */
    public function deletePosts($user, Thread $thread)
    {
        return $user->can('admin');
    }

    /**
     * Permission: Rename thread.
     *
     * @param  object  $user
     * @param  Thread  $thread
     * @return bool
     */
    public function rename($user, Thread $thread)
    {
        return $user->can('admin');
    }

    /**
     * Permission: Reply to thread.
     *
     * @param  object  $user
     * @param  Thread  $thread
     * @return bool
     */
    public function reply($user, Thread $thread)
    {
        return !$user->isNew;
    }

    /**
     * Permission: Delete thread.
     *
     * @param  object  $user
     * @param  Thread  $thread
     * @return bool
     */
    public function delete($user, Thread $thread)
    {
        return Gate::allows('deleteThreads', $thread->category);
    }
}
