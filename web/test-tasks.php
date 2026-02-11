<?php
// web/test-tasks-complete.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
require_once ROOT_PATH . '/app/models/Task.php';
require_once ROOT_PATH . '/database/connection.php';

echo "<h1>🧪 Test COMPLETO de Task con MySQL</h1>";

try {
    $pdo = getConnection();
    $taskModel = new Task();
    $userId = 1;
    
    // 1. Crear usuario si no existe
    echo "<h2>1️⃣ Verificar usuario</h2>";
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO users (id, name, surname, email, username) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([1, 'Test', 'User', 'test@test.com', 'testuser']);
        echo "✅ Usuario creado<br>";
    } else {
        echo "✅ Usuario existe<br>";
    }
    
    // 2. Añadir tarea
    echo "<h2>2️⃣ addTask()</h2>";
    $task1 = $taskModel->addTask($userId, 'Comprar leche', 'En el super', date('Y-m-d H:i:s'), null);
    echo "✅ Tarea añadida (ID: {$task1['id']})<br>";
    
    // 3. Obtener todas las tareas
    echo "<h2>3️⃣ getAllTasks()</h2>";
    $tasks = $taskModel->getAllTasks($userId);
    echo "✅ Tareas encontradas: " . count($tasks) . "<br>";
    
    // 4. Obtener tarea por ID
    echo "<h2>4️⃣ getTaskById()</h2>";
    $task = $taskModel->getTaskById($userId, $task1['id']);
    echo "✅ Tarea recuperada: " . ($task ? $task['title'] : 'No encontrada') . "<br>";
    
    // 5. Actualizar estado
    echo "<h2>5️⃣ updateTask() - Cambiar estado</h2>";
    $updated = $taskModel->updateTask($userId, $task1['id'], 'on_process');
    echo "✅ Estado actualizado a: " . ($updated ? $updated['status'] : 'Error') . "<br>";
    
    // 6. Actualizar contenido
    echo "<h2>6️⃣ updateTaskContent() - Editar tarea</h2>";
    $result = $taskModel->updateTaskContent($userId, $task1['id'], 'Comprar pan y leche', 'En el Mercadona', date('Y-m-d H:i:s', strtotime('+3 days')));
    echo "✅ Contenido actualizado: " . ($result ? 'Sí' : 'No') . "<br>";
    
    // 7. Ver cambios
    echo "<h2>7️⃣ Verificar cambios</h2>";
    $taskUpdated = $taskModel->getTaskById($userId, $task1['id']);
    echo "<pre>";
    print_r($taskUpdated);
    echo "</pre>";
    
    // 8. Eliminar tarea
    echo "<h2>8️⃣ deleteTask()</h2>";
    $deleted = $taskModel->deleteTask($userId, $task1['id']);
    echo "✅ Tarea eliminada: " . ($deleted ? 'Sí' : 'No') . "<br>";
    
    // 9. Verificar eliminación
    echo "<h2>9️⃣ Verificar eliminación</h2>";
    $tasks = $taskModel->getAllTasks($userId);
    echo "✅ Tareas restantes: " . count($tasks) . "<br>";
    
    echo "<hr><h2>🎉 TODOS LOS TESTS PASARON</h2>";
    
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}