<?php

namespace Clases;

require_once (__DIR__ . '/../autoload.php');

use \Clases\Publicacion;

/**
 * Clase que permite establecer el puntero de PDO y extraer datos de la base de datos
 */
class BaseDeDatos {

    /**
     * @var string $nombreBBDD nombre de la base de datos
     */
    private $nombreBBDD;

    /**
     * @var string $usuario nombre del usuario de la base de datos
     */
    private $usuario;

    /**
     * @var string $password contraseña para acceder a la base de datos
     */
    private $password;

    /**
     * @var string $servidor servidor en el que está la base de datos
     */
    private $servidor;

    /**
     * @var int $rol Rol de la conexión a la base de datos
     */
    private $rol;

    /**
     * @var PDO $pdo puntero a la base de datos
     */
    private $pdo;

    /**
     * Constructor de la clase para usar base de datos
     * 
     * Este constructor nos permite crear objetos de conexion a base de datos
     * en donde le pasamos un parametro rol que tiene un valor establecido igual
     * a cero que es el de conexion. Si se le pasa otro será un rol de usuario o 
     * gestor que le permitirá acceder a otros privilegios distintos al de conexión.
     * 
     * @author Marlene y David
     * @version php7
     * 
     * @param int $rol Variable para la conexion a base de datos con variable predeterminada para el rol de conexion
     * 
     * Crea un objeto o da un mensaje de error en la lectura del xml o en guardar los atributos
     * 
     * 
     * @return object objeto tipo PDO
     * 
     */
    public function __construct(int $rol = 1) {
        try {
            $data = $this->leer_configuracion(__DIR__ . '/../archive/config/configuracion.xml', __DIR__ . '/../archive/config/configuracion.xsd', $rol);
            $this->nombreBBDD = $data['nombreBBDD'];
            $this->usuario = $data['usuario'];
            $this->password = $data['pwd'];
            $this->servidor = $data['servidor'];
            $this->rol = $rol;
            $this->pdo = $this->conexion();
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función para conectar la base de datos
     * 
     * Esta función nos permite establecer los parámetros y a continuación crear el puntero PDO
     * 
     * @return PDO retorna la conexion de la base de datos
     * @see __construct()
     */
    function conexion() {
        try {
            $pdo = new \PDO("mysql:dbname=$this->nombreBBDD;host=$this->servidor", $this->usuario, $this->password);
            return $pdo;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función para leer la configuración del XML
     * 
     * Función que extrae del fichero XML los datos de conexión y los valida con el XSD
     * 
     * @param mixed $xml Ruta del fichero XML
     * @param mixed $xsd Ruta del fichero XSD
     * @param int $rol Variable que almacena el rol para buscar los datos en el fichero XML
     * 
     * @return array Array de los datos para la conexion del puntero PDO
     * @see __construct()
     */
    function leer_configuracion($xml, $xsd, $rol) {
        $conf = new \DOMDocument();
        $conf->load($xml);
        if (!$conf->schemaValidate($xsd)) {
            throw new PDOException("Ficheiro de usuarios no valido");
        }
        $xml = simplexml_load_file($xml);
        //Conversion a cadena de texto con "", porque si no, el xpath nos devuelve
        //un objeto de tipo SimpleXMLElement
        $array = [];
        $array['usuario'] = "" . $xml->xpath('//nombre[../rol="' . $rol . '"]')[0];
        $array['pwd'] = "" . $xml->xpath('//password[../rol="' . $rol . '"]')[0];
        $array['nombreBBDD'] = "" . $xml->xpath('//baseNombre[../rol="' . $rol . '"]')[0];
        $array['servidor'] = "" . $xml->xpath('//servidor[../rol="' . $rol . '"]')[0];
        return $array;
    }

    /**
     * Funcion que comprueba el usuario
     * 
     * Realiza una busqueda del usuario indicado en la base de datos y verifica que la contraseña es correcta. 
     * Si todo es correcto, nos devuelve el rol para futuras conexiones a la base de datos
     * 
     * @param string $email email del usuario a loguearse
     * @param string $password contraseña del usuario 
     * 
     * @return Devuelve false si los datos no son correctos o un array con el nombre y el rol
     */
    function comprobar_usuario($email, $password) {

        try {
            $pdo = $this->pdo;
            $sql = "SELECT ID_USUARIO,PWD,ROL_DE_USUARIO FROM usuarios WHERE E_MAIL=?";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute(array($email))) {
                if ($fila = $stmt->fetch()) {
                    $id = $fila['ID_USUARIO'];
                    $hash = $fila['PWD'];
                    $rol = $fila['ROL_DE_USUARIO'];
                    //Tenemos el rol y la pwd condificada de la BBDD
                    if (password_verify($password, $hash)) {
                        return array($id, $email, $rol);
                    } else {
                        return false;
                    }
                }
            } else {
                print_r($pdo->errorInfo());
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    function getDataPublicacion() {

        try {
            $pdo = $this->pdo;
            $sql = "SELECT ID_PUBLICACION,TITULO,DESCRIPCION,URL_PUBLICACION,USUARIO_PROPIETARIO,FECHA_PUBLICACION FROM publicaciones WHERE ID_PUBLICACION=?";
            $stmt = $pdo->prepare($sql);
            $numPublicaciones = $this->countPublicaciones();
            $numRandom = rand(1, $numPublicaciones);
            $resultPublicaciones = false;
            if (isset($_SESSION['id_publicacionValorada'])) {
                while (!$resultPublicaciones) {
                        if ($numRandom == $_SESSION['id_publicacionValorada']) {
                            $numRandom = rand(1, $numPublicaciones);
                        } else {
                            $resultPublicaciones = true;
                        }
                    }
                
            } else {
                $resultPublicaciones = true;
            }

            /* if(isset($_SESSION['id_publicacionValorada']) && count($_SESSION['id_publicacionValorada']) > 0){
              //$num_publicaciones = count($_SESSION['publicacion']) + 1;
              $_SESSION['publicacion'][] = $numRandom;
              }else{
              $_SESSION['publicacion'][0] = $numRandom;
              } */
            if ($resultPublicaciones) {
                if ($stmt->execute(array($numRandom))) {
                    if ($fila = $stmt->fetch()) {
                        $id = $fila['ID_PUBLICACION'];
                        $titulo = $fila['TITULO'];
                        $descripcion = $fila['DESCRIPCION'];
                        $url_descripcion = $fila['URL_PUBLICACION'];
                        $id_usuario_propietario = $fila['USUARIO_PROPIETARIO'];
                        $usuario_propietario = $this->get_usser($id_usuario_propietario)[0];
                        $fecha_publicacion = $fila['FECHA_PUBLICACION'];
                        $publicacion = new Publicacion($id, $titulo, $descripcion, $url_descripcion, $usuario_propietario, $fecha_publicacion);
                        return $publicacion;
                    }
                } else {
                    print_r($pdo->errorInfo());
                }
            } else {
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        } finally {
            $stmt = null;
            $pdo = null;
        }
    }

    function countPublicaciones() {
        try {

            $bd = $this->pdo;
            $bd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT COUNT(*) FROM publicaciones";
            if ($stmt = $bd->query($sql)) {
                $result = $stmt->fetchColumn();
                return $result;
            } else {
                echo $bd->errorInfo();
                return false;
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    function get_usser($id) {

        try {
            $bd = $this->pdo;
            $sql = "SELECT NOMBRE FROM usuarios WHERE ID_USUARIO='$id'";
            if ($stmt = $bd->query($sql)) {
                return $stmt->fetch();
            } else {
                echo $stmt->errorInfo()[0];
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    function introducirValoracion($id, $id_publicacion, $mensaje, $puntuacion) {
        $return = true;
        $valoracion = "";
        if ($puntuacion == 1) {
            $valoracion = 'NO APTA';
        } else if ($puntuacion == 2) {
            $valoracion = 'BIEN';
        } else if ($puntuacion == 3) {
            $valoracion = 'MUY BIEN';
        } else if ($puntuacion == 4) {
            $valoracion = 'EXCELENTE';
        }
        $bd = $this->pdo;
        $ins = "INSERT INTO usuarios_valora_publicaciones(USUARIO,PUBLICACION,VALORACION,MENSAJE_TEXTO) values(?,?,?,?)";
        if ($stmt = $bd->prepare($ins)) {
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $id_publicacion);
            $stmt->bindParam(3, $valoracion);
            $stmt->bindParam(4, $mensaje);
            if (!$stmt->execute()) {
                $error = $stmt->errorInfo();
                $return = false;
                echo $error[2];
                echo 'Se para en execute';
            }
        } else {
            $return = false;
        }
        return $return;
    }

    /**
     * Función para la fecha de sesión
     * 
     * Permite guardar la fecha de la última sesión de cada usuario 
     * 
     * @param string $email email del usuario que inicia la sesión
     * 
     * @return boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     */
    function set_fecha_sesion($email) {
        try {
            $fecha_sesion = date('Y-m-d');
            $bd = $this->pdo;
            $sql = "UPDATE usuarios SET fecha_sesion=? WHERE email=?";
            $stmt = $bd->prepare($sql);
            $stmt->bindParam(1, $fecha_sesion);
            $stmt->bindParam(2, $email);
            if ($stmt->execute()) {
                return true;
            } else {
                echo $bd->errorInfo()[0];
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función para la fecha de modificación
     * 
     * Permite guardar en la base de datos la fecha de la última modificación de perfil del usuario
     * 
     * @param string $email email del usuario que modifica su perfil
     * 
     * @return  boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     */
    function set_fecha_modify($email) {
        try {
            $fecha_modificacion = date('Y-m-d');
            $bd = $this->pdo;
            $sql = "UPDATE usuarios SET fecha_modificacion=? WHERE email=?";
            $stmt = $bd->prepare($sql);
            $stmt->bindParam(1, $fecha_modificacion);
            $stmt->bindParam(2, $email);
            if ($stmt->execute()) {
                return true;
            } else {
                echo $bd->errorInfo()[0];
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función de datos del usuario
     * 
     * Permite, por medio del email del usuario, extraer toda su información de la base de datos.
     * Se visualiza en un formulario en donde puede modificar el usuario sus propios datos
     * 
     * @param string $email email del usuario
     * 
     * @return array array con los datos del usuario de la base de datos
     */
    function get_data_usser($email) {
        $arrayData;
        $text = "";
        $bd = $this->pdo;
        $sql = "SELECT id,nombre,email,telf,direccion,password FROM usuarios WHERE email='$email'";
        if ($stmt = $bd->query($sql)) {
            $result = $stmt->fetch();
            $id = $result['id'];
            $name = $result['nombre'];
            $telf = $result['telf'];
            $direccion = $result['direccion'];
            $rol = $result['rol_usuario'];
            $text .= "<form action='perfil.php' method='POST'><td><input type='hidden' name='id' value='$id'/><input type='text' name='name' value='$name'/></td><td><input type='text' name='email' value='$email'/></td><td><input type='text' name='telf' value='$telf'/></td><td><input type='text' name='direccion' value='$direccion'/></td><td><input type='submit' name='modify' value='Modificar'/></td></form>";
            $arrayData = [
                "text" => $text, "id_usuario" => $id
            ];
            return $arrayData;
        } else {
            echo $bd->errorInfo()[0];
        }
    }

    /**
     * Función de modificar usuario
     * 
     * Permite al usuario modificar sus propios datos e insertarlos en la base de datos
     * 
     * @param int $id numero de usuario   
     * @param string $name nombre del usuario
     * @param string $email email del usuario
     * @param int $telf telefono del usuario
     * @param string $direccion dirección del usuario
     * 
     * @return boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     */
    function modify_usser($id, $name, $email, $telf, $direccion) {
        try {
            $fecha_modificacion = date('Y-m-d');
            $bd = $this->pdo;
            $sql = "UPDATE usuarios SET nombre=?,email=?,telf=?,direccion=?, fecha_modificacion=? WHERE id=?";
            $stmt = $bd->prepare($sql);
            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $telf);
            $stmt->bindParam(4, $direccion);
            $stmt->bindParam(5, $fecha_modificacion);
            $stmt->bindParam(6, $id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

//REGISTRO DE USUARIOS

    /**
     * Función para registrar al usuario
     * 
     * Permite guardar en la base de datos, la información de un nuevo usuario
     * que se registra en la aplicación. Primero comprueba si ya existe
     * 
     * @param string $name nombre del usuario
     * @param string $email email del usuario
     * @param int $telf teléfono del usuario
     * @param string $direccion dirección del usuario
     * @param string $pwd contraseña del usuario
     * 
     * @return  boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     */
    function registrar_usuario($name, $email, $telf, $direccion, $pwd) {
        if ($exists = $this->check_data_exists($email)) {
            return false;
        } else {
            if ($this->introduce_data($name, $email, $telf, $direccion, $pwd)) {
                return true;
            } else {
                throw new \RuntimeException('Error al introducir usuario');
            }
        }
    }

    /**
     * Función comprobar si existe usuario
     * 
     * Permite buscar si en la base de datos ya existe un usuario con el email pasado por parámetro
     * 
     * @param string $email email del usuario que se quiere registrar, el cual se va a comprobar si
     * ya existe
     * 
     * @return boolean devuelve true si existe el email en la tabla usuarios, sinó, devuelve false
     * @see registrar_usuario()
     */
    function check_data_exists($email) {
        $bd = $this->pdo;
        $bd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $ins = 'SELECT E_MAIL FROM usuarios';
        if (!$stmt = $bd->query($ins)) {
            echo $bd->errorInfo()[0];
        }
        $exists = false;
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            if ($row['E_MAIL'] === $email) {
                $exists = true;
            }
        }
        return $exists;
    }

    /**
     * Función para introducir usuario en la base de datos
     * 
     * @param string $name nombre del usuario
     * @param string $email email del usuario
     * @param int $telf teléfono del usuario
     * @param string $direccion dirección del usuario
     * @param string $pwd contraseña del usuario
     * 
     * @return boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     * @see registrar_usuario()
     */
    function introduce_data($name, $email, $telf, $direccion, $pwd) {
        //$fecha_sesion = date('Y-m-d');
        $rol = 1;
        $return = true;
        $bd = $this->pdo;
        $ins = "INSERT INTO usuarios(NOMBRE,E_MAIL,TELEFONO,DIRECCION,PWD,ROL_DE_USUARIO) values(?,?,?,?,?,?)";
        if ($stmt = $bd->prepare($ins)) {
            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $telf);
            $stmt->bindParam(4, $direccion);
            $stmt->bindParam(5, $pwd);
            $stmt->bindParam(6, $rol);
            if (!$stmt->execute()) {
                $error = $stmt->errorInfo();
                $return = false;
                echo $error[2];
            }
        } else {
            $return = false;
        }
        return $return;
    }

    /**
     * Función para no repetir la conexión
     * 
     * Permite realizar una sola conexión a la base de datos para mostrar los 
     * diferentes paneles de información para el gestor
     * 
     * @param string $sql consulta a la base de datos
     * 
     * @return mixed Devuelve un PDOstament si la consulta se realizó. Sinó retorna false
     */
    function consulta($sql) {
        //Iniciamos consulta
        $bd = $this->pdo;
        if (!$stmt = $bd->query($sql)) {
            return FALSE;
        } else {
            return $stmt;
        }
    }

    /**
     * Función modificación de usuarios
     * 
     * Permite al gestor modificar los datos de un usuario en el panel por medio de su id
     * 
     * @param int $data Variable que almacena el id del usuario
     * 
     * @return boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     */
    function modify_ussers($data) {
        try {
            $bd = $this->pdo;
            $sql1 = "UPDATE usuarios SET nombre=?,email=?,telf=?,direccion=?,rol_usuario=? WHERE id=?";
            $bd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $stmt = $bd->prepare($sql1);
            $stmt->bindParam(1, $data[1]);
            $stmt->bindParam(2, $data[2]);
            $stmt->bindParam(3, $data[3]);
            $stmt->bindParam(4, $data[4]);
            $stmt->bindParam(5, $data[5]);
            $stmt->bindParam(6, $data[0]);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función modificar habitación
     * 
     * Permite al gestor modificar todos los datos de una habitación en el panel
     * 
     * @param array $data array de los datos de la habitación para modificar
     * 
     * @return boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     */
    function modify_rooms($data) {
        try {
            $bd = $this->pdo;
            $sql1 = "UPDATE habitaciones SET m2=?,ventana=?,tipo_de_habitacion=?,servicio_limpieza=?,internet=?,precio=?,reservable=? WHERE id =?";
            $bd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $stmt = $bd->prepare($sql1);
            $stmt->bindParam(1, $data[1]);
            $stmt->bindParam(2, $data[2]);
            $stmt->bindParam(3, $data[3]);
            $stmt->bindParam(4, $data[4]);
            $stmt->bindParam(5, $data[5]);
            $stmt->bindParam(6, $data[6]);
            $stmt->bindParam(7, $data[7]);
            $stmt->bindParam(8, $data[0]);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función modificar la reserva
     * 
     * Permite al gestor modificar el estado de la reseva a aceptada o denegada
     * 
     * @param array $data aray con el número de la reserva y es estado nuevo
     * 
     * @return boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     */
    function modify_register($data) {
        try {
            $bd = $this->pdo;
            $sql = "UPDATE reservas SET estado=? WHERE num_reserva=?";
            $stmt = $bd->prepare($sql);
            $estado = (int) $data[1];
            $num_reserva = (int) $data[0];
            if (!$stmt->execute(array($estado, $num_reserva))) {
                echo $stmt->errorInfo()[0];
                return false;
            } else {
                return true;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función modificar los servicios
     * 
     * Permite al gestor cambiar los datos de un servicio en el panel
     * 
     * @param array $data Array con los datos del servicio que se quiere modificar
     * 
     * @return boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     */
    function modify_services($data) {
        try {
            $bd = $this->pdo;
            $sql1 = "UPDATE servicios SET nombre_servicio=?,precio_servicio=?,descripcion=?,disponibilidad=? WHERE id=?";
            $bd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $stmt = $bd->prepare($sql1);
            $stmt->bindParam(1, $data[1]);
            $stmt->bindParam(2, $data[2]);
            $stmt->bindParam(3, $data[3]);
            $stmt->bindParam(4, $data[4]);
            $stmt->bindParam(5, $data[0]);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función borrar habitaciones
     * 
     * Permite al gestor borrar una habitación de la base de datos
     * 
     * @param int $id número de la habitación que se desea borrar
     * 
     * @return boolean devuelve true si se ejecutó con exito la sentencia de la base de datos
     */
    function delete_rooms($id) {
        try {
            $bd = $this->pdo;
            $sql = "DELETE FROM habitaciones WHERE id=$id";
            $result = $bd->exec($sql);
            if ($result === false) {
                echo $bd->errorInfo()[0];
            } else {
                return true;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función para añadir havitación
     * 
     * Permite al gestor introducir una habitación en la base de datos
     * @param array $data array con los datos de la habitación
     * 
     * @return boolean devuelve true si se ejecutó con exito la sentencia de la base de datos, sinó, devuelve false
     */
    function add_room($data) {
        try {
            $bd = $this->pdo;
            $sql1 = "INSERT INTO habitaciones (m2,ventana,tipo_de_habitacion,servicio_limpieza,internet,precio,reservable) VALUES (?,?,?,?,?,?,?)";
            $bd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $stmt = $bd->prepare($sql1);
            $stmt->bindParam(1, $data[0]);
            $stmt->bindParam(2, $data[1]);
            $stmt->bindParam(3, $data[2]);
            $stmt->bindParam(4, $data[3]);
            $stmt->bindParam(5, $data[4]);
            $stmt->bindParam(6, $data[5]);
            $stmt->bindParam(7, $data[6]);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función para buscar habitaciones
     * 
     * Permite al usuario buscar una habitación por tipo, indicando una fecha de entrada y otra de salida,
     * para después mostrar los datos de cada una encontrada
     * 
     * @param date $entrada Fecha de entrada de la reserva
     * @param date $salida Fecha de salida de la reserva
     * @param int $id_tipo Tipo de habitación a buscar
     * 
     * @return array Devuelve un array con los datos de cada una de las habitaciones que está libres entre esas fechas
     */
    function get_rooms($entrada, $salida, $id_tipo) {
        try {
            $bd = $this->pdo;
            $bd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $sql = "select * from habitaciones where id not in "
                    . "(select hr.id_habitacion from reservas as v inner join habitaciones_reservas as hr "
                    . "where v.num_reserva like hr.num_reserva and ? >= v.fecha_entrada and ? <= v.fecha_salida "
                    . "and ? >= v.fecha_entrada) and habitaciones.reservable = 1 and habitaciones.tipo_de_habitacion = ?;";
            if (($stmt = $bd->prepare($sql))) { // Creamos y validamos la sentencia preparada
                $stmt->bindValue(1, $entrada, \PDO::PARAM_STR);
                $stmt->bindValue(2, $entrada, \PDO::PARAM_STR);
                $stmt->bindValue(3, $salida, \PDO::PARAM_STR);
                $stmt->bindValue(4, $id_tipo, \PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $arrayHabitaciones = [];
                    $arrayServicios = [];
                    while ($row = $stmt->fetch(\PDO::FETCH_BOTH)) {
                        $arrayRuta = $this->get_ruta_img($row['tipo_de_habitacion']);
                        $descripcion = $this->get_description_room($row['tipo_de_habitacion']);
                        $habitacion = new habitaciones((int) $row['id'], (float) $row['m2'], (boolean) $row['ventana'], $row['tipo_de_habitacion'], $descripcion['descripcion'], $row['servicio_limpieza'], $row['internet'], (float) $row['precio'], $row['reservable'], $arrayServicios, $arrayRuta);
                        array_push($arrayHabitaciones, $habitacion);
                    }
                    return $arrayHabitaciones;
                } else {
                    echo $bd->errorInfo()[0];
                }
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función para obtener la ruta de las imagenes
     * 
     * Permite extraer la ruta en donde están las imagenes de cada tipo de habitación
     * para después mostrarlas al visualizar las habitaciones disponibles
     * 
     * @param string $room_type tipo de habitación seleccionada
     * 
     * @return string ruta de las imagenes
     */
    function get_ruta_img($room_type) {
        try {

            $bd = $this->pdo;
            $bd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT imagen_habitacion FROM imagenes_habitaciones "
                    . "WHERE id_habitacion_tipo=(SELECT id FROM habitacion_tipo WHERE tipo_habitacion=?)";
            $stmt = $bd->prepare($sql);
            if ($stmt->execute(array($room_type))) {
                $result = $stmt->fetch();
                $ruta = $result['imagen_habitacion'];
            } else {
                echo $bd->errorInfo();
            }

            //Crear funcion buscar_imagenes (parametro tipo_habitacion)
            //Consulta para extraer ruta y añadir a la variable(parametro ruta)
            //Crear funcion mostrar_imagenes(parametro ruta, rol)
            $folder_path = $ruta . "/"; //image's folder path
            //echo "$folder_path";
            $num_files = glob($folder_path . "*.{JPG,jpg,gif,png,bmp,jpeg,jfif}", GLOB_BRACE);
            //var_dump($num_files);
            $folder = opendir($folder_path);
            //var_dump($folder);
            //var_dump(readdir($folder));
            $arrayFotos = [];
            if ($num_files > 0) {
                while (false !== ($file = readdir($folder))) {
                    $file_path = $folder_path . $file;
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'bmp' || $extension == 'jpeg' || $extension == 'jfif') {
                        //otro if para ver el parametro y como mostrar las fotos(añadir a una variable para imprimirla despues)
                        array_push($arrayFotos, $file_path);
                    }
                }
                return $arrayFotos;
            } else {
                echo "the folder was empty !";
            }
            closedir($folder);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     * Función obtener la descripcion de la habitación
     * 
     * Permite obtener de la base de datos, la descripción del tipo de habitación seleccionada
     * 
     * @param string $room_type tipo de habitación seleccionada
     * 
     * @return string texto de la descripción
     */
    function get_description_room($room_type) {
        try {
            $bd = $this->pdo;
            $sql = "SELECT descripcion FROM habitacion_tipo WHERE tipo_habitacion='$room_type'";
            if ($stmt = $bd->query($sql)) {
                return $stmt->fetch();
            } else {
                echo $stmt->errorInfo()[0];
            }
        } catch (\PDOException $ex) {
            echo $ex->getMessage();
        }
    }

}
