<?php

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */
$routes = array(

	// test
	'/test' => 'test#index',

	// auth
	'/' => 'auth#login',
	'/auth/register' => 'auth#register',
	'/auth/logout' => 'auth#logout',

	// dashboard
	'/dashboard' => 'dashboard#index',

	// tasks
	'/task' => 'task#index',
	'/task/new' => 'task#new',
	'/task/add' => 'task#addTask',
	'/task/edit' => 'task#editTask',
	'/task/update' => 'task#updateTask',
	'/task/update-content' => 'task#updateTaskContent',
	'/task/delete' => 'task#deleteTask',

	// users
	'/users' => 'user#index',
	'/users/login-as' => 'user#loginAs',
	'/users/add' => 'user#add',
	'/users/edit' => 'user#edit',
	'/users/delete' => 'user#delete',
	'/users/delete-all' => 'user#deleteAll',
);
