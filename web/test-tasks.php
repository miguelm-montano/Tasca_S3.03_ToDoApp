<?php
// web/test-tasks.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
require_once ROOT_PATH . '/app/models/Task.php';

echo "<h1>Test de Task con MySQL</h1>";

try {
    $taskModel = new Task();
    
    // Test 1: Obtener tareas (debería estar vacío)
    echo "<h2>Test 1: getAllTasks() - ANTES de añadir</h2>";
    $tasks = $taskModel->getAllTasks(1);
    echo "Tareas encontradas: " . count($tasks) . "<br>";
    echo "<pre>";
    print_r($tasks);
    echo "</pre>";
    
    // Test 2: Añadir una tarea nueva
    echo "<h2>Test 2: addTask() - Añadiendo tarea de prueba</h2>";
    $newTask = $taskModel->addTask(
        1,                           // userId
        'Tarea de prueba',          // title
        'Esta es una descripción',  // description
        date('Y-m-d H:i:s'),        // createdAt
        date('Y-m-d H:i:s', strtotime('+7 days'))  // dueDate
    );
    echo "✅ Tarea añadida:<br>";
    echo "<pre>";
    print_r($newTask);
    echo "</pre>";
    
    // Test 3: Obtener tareas de nuevo (ahora debería haber 1)
    echo "<h2>Test 3: getAllTasks() - DESPUÉS de añadir</h2>";
    $tasks = $taskModel->getAllTasks(1);
    echo "Tareas encontradas: " . count($tasks) . "<br>";
    echo "<pre>";
    print_r($tasks);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}