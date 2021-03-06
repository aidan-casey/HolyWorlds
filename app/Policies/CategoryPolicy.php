<?php
namespace App\Policies;

use App\Http\Middleware\Permissions;
use App\Models\Forum\Category;

class CategoryPolicy
{
	protected $permissionPrefix = 'forum.category';

	/**
	 * Permission: Create threads in category.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function createThreads($user, Category $category)
	{
		return $this->checkPermission( 'thread_create', $category->id );;
	}

	/**
	 * Permission: Manage threads in category.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function manageThreads($user, Category $category)
	{
		return $this->deleteThreads($user, $category) ||
			   $this->enableThreads($user, $category) ||
			   $this->moveThreadsFrom($user, $category) ||
			   $this->lockThreads($user, $category) ||
			   $this->pinThreads($user, $category);
	}

	/**
	 * Permission: Delete threads in category.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function deleteThreads($user, Category $category)
	{
		return $this->checkPermission( 'thread_delete', $category->id );
	}

	/**
	 * Permission: Enable threads in category.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function enableThreads($user, Category $category)
	{
		return $this->checkPermission( 'thread_enable', $category->id );
	}

	/**
	 * Permission: Move threads from category.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function moveThreadsFrom($user, Category $category)
	{
		return $this->checkPermission( 'thread_move_from', $category->id );
	}

	/**
	 * Permission: Move threads to category.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function moveThreadsTo($user, Category $category)
	{
		return $this->checkPermission( 'thread_move_to', $category->id );
	}

	/**
	 * Permission: Lock threads in category.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function lockThreads($user, Category $category)
	{
		return $this->checkPermission( 'thread_lock', $category->id );
	}

	/**
	 * Permission: Pin threads in category.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function pinThreads($user, Category $category)
	{
		return $this->checkPermission( 'thread_pin', $category->id );
	}

	/**
	 * Permission: View category. Only takes effect for 'private' categories.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function view($user, Category $category)
	{
		return $this->checkPermission( 'view', $category->id );
	}

	/**
	 * Permission: Delete category.
	 *
	 * @param  object  $user
	 * @param  Category  $category
	 * @return bool
	 */
	public function delete($user, Category $category)
	{
		return $this->checkPermission( 'delete', $category->id );
	}

	public function checkPermission( $action, $id )
	{
		return Permissions::checkPermission( $this->permissionPrefix . '.' . $action . '.' . $id ) !== null;
	}
}
