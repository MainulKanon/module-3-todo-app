<?php
$tasks = [];
$filename = 'tasks.json';

if (file_exists($filename)) {
    $json = file_get_contents($filename);
    $tasks = json_decode($json, true);
}

// Redirect to self after post actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['task']) && trim($_POST['task']) !== '') {
        $task = htmlspecialchars(trim($_POST['task']));
        $tasks[] = ['name' => $task, 'done' => false];
    } elseif (isset($_POST['toggle'])) {
        $index = $_POST['toggle'];
        $tasks[$index]['done'] = !$tasks[$index]['done'];
    } elseif (isset($_POST['delete'])) {
        $index = $_POST['delete'];
        array_splice($tasks, $index, 1);
    }

    file_put_contents($filename, json_encode($tasks, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>To-Do App</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
  <style>
    body { max-width: 600px; margin: 40px auto; }
    .done { text-decoration: line-through; color: gray; }
    form.inline { display: inline; }
  </style>
</head>
<body>
  <h2>📝 Simple To-Do App</h2>
  <form method="POST">
    <input type="text" name="task" placeholder="Enter new task" required>
    <button type="submit">Add Task</button>
  </form>

  <ul>
    <?php foreach ($tasks as $index => $task): ?>
      <li>
        <form method="POST" class="inline">
          <button type="submit" name="toggle" value="<?= $index ?>" style="border:none;background:none;">
            <span class="<?= $task['done'] ? 'done' : '' ?>"><?= $task['name'] ?></span>
          </button>
        </form>
        <form method="POST" class="inline">
          <button type="submit" name="delete" value="<?= $index ?>" style="color:red;">❌</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
