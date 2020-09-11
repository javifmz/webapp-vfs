<?php

$api_path = getenv('API_PATH') ?: '/app';

$database_config = [
  'driver'    => getenv('DB_DRIVER')    ?: 'mysql',
  'host'      => getenv('DB_HOST')      ?: '127.0.0.1',
  'port'      => getenv('DB_PORT')      ?: '3306',
  'charset'   => getenv('DB_CHARSET')   ?: 'utf8',
  'collation' => getenv('DB_COLLATION') ?: 'utf8_unicode_ci',
  'database'  => getenv('DB_DATABASE'),
  'username'  => getenv('DB_USERNAME'),
  'password'  => getenv('DB_PASSWORD'),
  'prefix'    => '',
];

if(getenv('ADMIN_INIT')) {
  $admin = [
    'email' => getenv('ADMIN_EMAIL')       ?: 'admin@example.com',
    'username' => getenv('ADMIN_USERNAME') ?: 'admin',
    'password' => getenv('ADMIN_PASSWORD') ?: 'admin'
  ];
  error_log(json_encode($admin));
}

$max_tries = 10;
$connection_fail = true;
do {
  try {
    $pdo = new PDO('mysql:host=' . $database_config['host'] . ';port=' . $database_config['port'] . ';dbname=' . $database_config['database'], $database_config['username'], $database_config['password']);
    $connection_fail = false;
    error_log('Database connection success');
  } catch(PDOException $ex) {
    $max_tries--;
		if ($max_tries <= 0) {
      error_log('Database connection error');
      exit(1);
    } else {
      error_log('Database connection error, waiting 3 seconds to retry...');
      sleep(3);
    }
  }
} while ($connection_fail);

$result = $pdo->query('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = "' . $database_config['database'] . '" AND TABLE_NAME = "migration";');
if(!$result->fetch()) {
  error_log('Database migrations are not initialised, creating...');
  $script = file_get_contents($api_path . '/migrations/_create.sql');
  $pdo->exec($script);
  error_log('Database migrations initialised successfully');
}

if($admin) {
  $password = password_hash($admin['password'], PASSWORD_DEFAULT);
  $result = $pdo->query('SELECT id FROM user WHERE id = 1 LIMIT 1;');
  if(!$result->fetch()) {
    $pdo->query('INSERT INTO user (id, email, username, password) VALUES (1, "' . $admin['email'] . '", "' . $admin['username'] . '", "' . $password . '");');
    error_log('Database admin user initialised successfully');
  } else {
    $pdo->query('UPDATE user SET email = "' . $admin['email'] . '", username = "' . $admin['username'] . '", password = "' . $password . '" WHERE id = 1;');
    error_log('Database admin user updated successfully');
  }
}

$migrations = scandir($api_path . '/migrations');
foreach($migrations as $migration) {
  $extension = pathinfo($migration)['extension'];
  $id = pathinfo($migration)['filename'];
  if("sql" === $extension && $id !== '_create') {
    $result = $pdo->query('SELECT id FROM migration where id = "' . $id . '";');
    if(!$result->fetch()) {
      $script = file_get_contents($api_path . '/migrations/' . $id . '.' . $extension);
      $pdo->exec($script);
      $pdo->query('INSERT INTO migration VALUES ("' . $id . '");');
      error_log('Database migration "' . $id . '" executed successfully ');
    }
  }
}
