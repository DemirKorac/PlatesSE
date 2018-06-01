<?php
class PersistenceManager{

  protected $pdo;

  public function __construct($params){
    try{
      $this->pdo = new PDO("mysql:host=".$params['host'].";dbname=".$params['schema'].";charset=utf8", $params['username'], $params['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    }catch(PDOException  $e ){
      echo "Error: ".$e;
    }
  }

  private function execute($query, $params){
    $prepared_statement = $this->pdo->prepare($query);
    if ($params){
      foreach($params as $key => $param){
        $prepared_statement->bindValue($key, $param);
      }
    }
    $prepared_statement->execute();
    return $prepared_statement;
  }
    public function getdata($email){
        return $this->query_single('SELECT registration,daily,monthly,yearly FROM people WHERE email = :email', [':email' => $email]);
    }
    public function getentrymonth($registration){
      return $this->query_single('SELECT COUNT(datetime) as number_of_entries_month FROM activities WHERE datetime BETWEEN (DATE_SUB(NOW(), INTERVAL 1 month)) AND CURRENT_TIMESTAMP AND registration= :registration', [':registration' => $registration]);
  }
  public function getentryday($registration){
    return $this->query_single('SELECT COUNT(datetime) as number_of_entries_day FROM activities WHERE datetime BETWEEN (DATE_SUB(NOW(), INTERVAL 1 day)) AND CURRENT_TIMESTAMP AND registration= :registration', [':registration' => $registration]);
}
public function getentryyear($registration){
  return $this->query_single('SELECT COUNT(datetime) as number_of_entries_year FROM activities WHERE datetime BETWEEN (DATE_SUB(NOW(), INTERVAL 1 year)) AND CURRENT_TIMESTAMP AND registration= :registration', [':registration' => $registration]);
}
public function getentrytotal($registration){
  return $this->query_single('SELECT COUNT(datetime) as number_of_entries_day FROM activities WHERE registration= :registration', [':registration' => $registration]);
}
    public function getactivities($registration){
        return $this->query('SELECT activities.registration,activities.datetime,activities.action FROM activities INNER JOIN people ON activities.registration=people.registration WHERE people.email = :registration LIMIT 5', [':registration' => $registration]);
    }
    public function getactivitiesall($registration){
      return $this->query('SELECT activities.registration,activities.datetime,activities.action FROM activities INNER JOIN people ON activities.registration=people.registration WHERE people.email = :registration LIMIT 12', [':registration' => $registration]);
  }
     public function getactivitiesfull($registration){
        return $this->query('SELECT activities.registration,activities.datetime,activities.action FROM activities INNER JOIN people ON activities.registration=people.registration WHERE people.email = :registration LIMIT 1', [':registration' => $registration]);
    }
    
  private function execute_insert($table, $record){
    $insert = 'INSERT INTO '.$table.' ('.implode(", ", array_keys($record)).')VALUES (:'.implode(", :",  array_keys($record)).')';
    $prepared_statement = $this->execute($insert, $record);
    return $this->pdo->lastInsertId();
  }
  public function query($query, $params){
    $prepared_statement = $this->execute($query, $params);
    return $prepared_statement->fetchAll();
  }
  public function query_single($query, $params){
    $result = $this->query($query, $params);
    return reset($result);
  }
  public function update($query, $params){
    $prepared_statement = $this->execute($query, $params);
  }

  public function get_user_by_email($email){
    return $this->query_single('SELECT * FROM people WHERE email = :email', [':email' => $email]);
  }

  public function update_user_by_email($email, $image, $google_id, $name){
    $this->execute('UPDATE people SET image=:image, google_id=:google_id, name = :name WHERE email = :email', [
      ':email' => $email,
      ':image' => $image,
      ':google_id' => $google_id,
      ':name' => $name
    ]);
  }
}

?>
