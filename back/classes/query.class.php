<?php

Class Query extends Connection{
     
 
      public function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return  $data;
    }


       protected function executeQuery($sql){
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $row=$stmt->fetchAll();
        return $row;
      }

}