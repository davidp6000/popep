<?php

namespace Clases;

class Publicacion{
    
    private $id;
    private $titulo;
    private $description;
    private $ruta;
    private $usuario;
    private $fecha;
    
    public function __construct($id, $titulo, $description, $img, $usuario, $fecha) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->description = $description;
        $this->ruta = $img;
        $this->usuario = $usuario;
        $this->fecha = $fecha;
    }
    
    function getData(){
        $arrayPublicaciones = [];
        $arrayPublicaciones['id'] = $this->id;
        $arrayPublicaciones['titulo'] = $this->titulo;
        $arrayPublicaciones['descripcion'] = $this->description;
        $arrayPublicaciones['img'] = $this->ruta;
        $arrayPublicaciones['usuario'] = $this->usuario;
        $arrayPublicaciones['fecha'] = $this->fecha;
        return $arrayPublicaciones;
    }

}

