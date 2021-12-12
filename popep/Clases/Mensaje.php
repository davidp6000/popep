<?php

namespace Clases;

class Mensaje{
    
    private $id;
    private $contenido;
    private $usuario;
    private $id_publicacion;
    private $id_usuarioCreadorPublicacion;
    
    public function __construct($id, $contenido, $usuario, $id_publicacion, $id_usuarioCreadorPublicacion) {
        $this->id = $id;
        $this->contenido = $contenido;
        $this->usuario = $usuario;
        $this->id_publicacion = $id_publicacion;
        $this->id_usuarioCreadorPublicacion = $id_usuarioCreadorPublicacion;
    }
    
    function getData(){
        $arrayMensajes = [];
        $arrayMensajes['id'] = $this->id;
        $arrayMensajes['contenido'] = $this->contenido;
        $arrayMensajes['usuario'] = $this->usuario;
        $arrayMensajes['id_publicacion'] = $this->id_publicacion;
        $arrayMensajes['id_usuarioCreadorPublicacion'] = $this->id_usuarioCreadorPublicacion;
        return $arrayMensajes;
    }

}

