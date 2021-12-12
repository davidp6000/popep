<?php
namespace Clases;

use \Interfaces\Obtener as obtener;


/**
* Clase que permite crear objetos de tipo usuario, en la cual se almacenarán los atributos de cada uno de ellos
 * así como de sus métodos
 */
class Usuario implements obtener {
  
    /**
     * @var int $id identificador del usuario
     */
    private $id;
    /**
     * @var string $nombre nombre del usuario
     */
    private $nombre;
    /**
     * @var string $email email del usuario
     */
    private $email;
    /**
     * @var int $telefono telefono del usuario
     */
    private $telefono;
    /**
     * @var string $direccion dirección del usuario
     */
    private $direccion;
    /**
     * @var string $contrasena contraseña del usuario
     */
    private $contrasena;
    /**
     * @var int $rol rol del usuario
     */
    private $rol;
    
    
    /**
     * Función del constructor del usuario
     * 
     * Permite crear un objeto de tipo usuario
     * 
     * @param mixed $id identificador del usuario
     * @param mixed $nombre nombre del usuario
     * @param mixed $email email del usuario
     * @param mixed $telefono telefono del usuario
     * @param mixed $direccion dirección del usuario
     * @param mixed $rol rol del usuario
     * 
     * @return object objeto tipo Usuario
     */
    function __construct($id,$nombre,$email,$telefono,$direccion,$rol) {
        $this->id=$id;
        $this->nombre=$nombre;
        $this->email=$email;
        $this->telefono=$telefono;
        $this->direccion=$direccion;
        $this->rol=$rol;
    }
    
    /**
     * Función de los datos del usuario
     * 
     * Permite obtener los datos del objeto usuarios para emplearlos
     * 
     * @return array array con los datos del usuario
     */
    function getData() {
        $arrayDataUsser = [];
        $arrayDataUsser['id'] = $this->id;
        $arrayDataUsser['nombre'] = $this->nombre;
        $arrayDataUsser['email'] = $this->email;
        $arrayDataUsser['telefono'] = $this->telefono;
        $arrayDataUsser['direccion'] = $this->direccion;
        $arrayDataUsser['rol'] = $this->rol;
        return $arrayDataUsser;
    }

    
    /**
     * Función toString
     * 
     * Permite extraer datos del usuario en forma de un text
     * 
     * @return string Texto con los datos del usuario
     */
    public function __toString() {
        $res="Nombre: ".$this->nombre."<br>Email: ".$this->email."<br>Telefono: ".$this->telefono."<br>Dirección: ".$this->direccion;
        return $res;
    }
  
}
