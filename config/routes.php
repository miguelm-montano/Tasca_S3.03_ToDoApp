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
	'/' => 'auth#login',                 // Login page
	'/dashboard' => 'dashboard#index',   // Dashboard personal
	'/auth/logout' => 'auth#logout',     // Logout
	'/users' => 'user#index',            // Vista de todos los usuarios
	'/users/add' => 'user#add',          // ← SIN slash (para match con vistas)
	'/users/add/' => 'user#add',         // ← CON slash (por si acaso)
	'/users/edit' => 'user#edit',        // ← SIN slash (para match con vistas)
	'/users/edit/' => 'user#edit',       // ← CON slash (por si acaso)
	'/users/delete' => 'user#delete',    // ← SIN slash (para match con vistas)
	'/users/delete/' => 'user#delete'    // ← CON slash (por si acaso)
);
