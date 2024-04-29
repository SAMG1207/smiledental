<?php

Class Disponibilidad extends Query{
    /**
     * Clase usada para importar a la base de datos la información de la disponibilidad de los distintos dentistas
     */
    private $id_dentista;
    private $dia;
    private $hora_inicio;
    private $hora_fin;
    public function __construct($id_dentista, $dia, $hora_inicio, $hora_fin){
         $this->id_dentista = $id_dentista;
         $this->dia = $dia;
         $this->hora_inicio = $hora_inicio;
         $this->hora_fin = $hora_fin;
    }

    public function insertDisponibilidad(){
        $sql = "INSERT INTO disponibilidad (id_dentista, dia, hora_inicio, hora_fin) VALUES (?,?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$this->id_dentista, $this->dia, $this->hora_inicio, $this->hora_fin]);
        $this->close();
        return true;
    }

    public function insertGeneral(){
        $sql = "INSERT INTO general (id_odontologo, hora_inicio, hora_fin) VALUES (?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$this->id_dentista, $this->hora_inicio, $this->hora_fin]);
        $this->close();
        return true;
    }

}  
