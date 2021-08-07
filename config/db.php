<?php session_id() == "" ? session_start() : null;
  use Medoo\Medoo;
  if(!function_exists('error')){
      function error($msg){
          ob_start();
          var_dump($msg);
        echo '<script>console.error(`'.ob_get_clean().'`);</script>';
      }
  }
  if(!function_exists("admin_password")){
      function generate_admin_password($password){
        $password = md5(base64_encode(md5($password)));
        return $password;
    }
  }
  try {
      // LIVE DB
    // $database =  new Medoo([
    //   // [required]
    //   'type' => 'mysql',
    //   'host' => 'localhost',
    //   'database' => '',
    //   'username' => '',
    //   'password' => ''
    // ]);
    // TEST DB
    $db =  new Medoo([
      // [required]
      'type' => 'mysql',
      'host' => 'localhost',
      'database' => 'abbank',
      'username' => 'root',
      'password' => '',
    ]);
  } catch (\Throwable $error) {
    error("There is an error in the db configuration file");
    error(json_encode(["error" => "$error"], true));
    exit("<p>Database connection failed. Contact our technical team for more information.");
  }
?>