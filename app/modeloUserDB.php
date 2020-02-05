<?php

include_once 'config.php';
include_once 'util.php';
include_once 'Cifrador.php';

class ModeloUserDB {

     private static $dbh = null; 
     private static $consulta_user = "Select identificador from Usuarios where identificador = ?";
     private static $consulta_useryclave = "Select identificador  from Usuarios where identificador = ?".
                                           " and clave =?";
     private static $consulta_email = "Select count(*) from Usuarios where identificador = ?";
     
    
public static function init(){
    
    if (self::$dbh == null){
        try {
            // Cambiar constantes a Config
            $dsn = "mysql:host=192.168.1.42;dbname=Pruebas";
            self::$dbh = new PDO($dsn, "root", "root");
            // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Error de conexión ".$e->getMessage();
            exit();
        }
        // Consultas Precompil
        
    }
    
}

// Comprueba usuario y contraseña son correctos (boolean)
public static function isOkUser($user,$clave){
    $stmt = self::$dbh->prepare(self::$consulta_useryclave);
    $clavecf= Cifrador::cifrar($clave);
    $stmt->bindValue(1,$user);
    $stmt->bindValue(2,$clavecf);
    $stmt->execute(); 
    if ($stmt->rowCount() > 0 ){
        return true;
    } 
    return false;
}

// Comprueba si ya existe un usuario con ese identificar
public static function existeID(String $user):bool{
    return false;
}

//Comprueba si existe en email en la BD
public static function existeEmail(String $user){
    return false;
}


/*
 * Chequea si hay error en el datos antes de guardarlos
 */
public static function errorValoresAlta ($user,$clave1, $clave2, $nombre, $email, $plan, $estado){
    if ( modeloExisteID($user))                         return TMENSAJES['USREXIST'];
    if ( preg_match("/^[a-zA-Z0-9]+$/", $user) == 0)    return TMENSAJES['USRERROR'];
    if ( $clave1 != $clave2 )                           return TMENSAJES['PASSDIST'];
    if ( !modeloEsClaveSegura($clave1) )                return TMENSAJES['PASSEASY'];
    if ( !filter_var($email, FILTER_VALIDATE_EMAIL))    return TMENSAJES['MAILERROR'];
    if ( modeloExisteEmail($email))                     return TMENSAJES['MAILREPE'];
    return false;
}

public static function errorValoresModificar($user, $clave1, $clave2, $nombre, $email, $plan, $estado){
    
    if ( $clave1 != $clave2 )                           return TMENSAJES['PASSDIST'];
    if ( !modeloEsClaveSegura($clave1) )                return TMENSAJES['PASSEASY'];
    if ( !filter_var($email, FILTER_VALIDATE_EMAIL))    return TMENSAJES['MAILERROR'];
    // SI se cambia el email
    $emailantiguo = modeloGetEmail($user);
    if ( $email != $emailantiguo && modeloExisteEmail($email))   return TMENSAJES['MAILREPE'];
    return false;
}

/*
 * Comprueba que la contraseña es segura
 */

public static function EsClaveSegura (String $clave):bool {
    if ( empty($clave))         return false;
    if (  strlen($clave) < 8 )  return false;
    if ( !hayMayusculas($clave) || !hayMinusculas($clave)) return false;
    if ( !hayDigito($clave))         return false;
    if ( !hayNoAlfanumerico($clave)) return false;
    
    return true;
}


/*
 * Comprueba si un correo existe
 */
public static function ExisteEmail( String $email):bool{
    foreach ($_SESSION['tusuarios'] as $clave => $datosusuario){
        if ($email == $datosusuario[2]) return true;
    }
    return false;
}




// Devuelve el plan de usuario (String)
public static function ObtenerTipo($user){
    $cod = $_SESSION['tusuarios'][$user][3];
    return PLANES[$cod];
}

// Borrar un usuario (boolean)
public static function UserDel($userid){
    if (isset($_SESSION['tusuarios'][$userid])){
        unset($_SESSION['tusuarios'][$userid]);
        return true;
    }
    return false;
}
// Añadir un nuevo usuario (boolean)
public static function UserAdd($userid, $userdat){
    
    if (! isset($_SESSION['tusuarios'][$userid])){
        $_SESSION['tusuarios'][$userid]= $userdat;
        return true;
    }
    return false; // Identificador repetido
}

// Actualizar un nuevo usuario (boolean)
public static function UserUpdate ($userid, $userdat){
    
    if ( isset($_SESSION['tusuarios'][$userid])){
        $_SESSION['tusuarios'][$userid]= $userdat;
        return true;
    }
    return false; // Identificador no existe
}


// Tabla de todos los usuarios para visualizar
public static function UserGetAll (){
    // Genero lo datos para la vista que no muestra la contraseña ni los códigos de estado o plan
    // sino su traducción a texto
    $tuservista=[];
    foreach ($_SESSION['tusuarios'] as $clave => $datosusuario){
        $tuservista[$clave] = [$datosusuario[1],
            $datosusuario[2],
            PLANES[$datosusuario[3]],
            ESTADOS[$datosusuario[4]]
        ];
    }
    return $tuservista;
}



// Datos de un usuario para visualizar
public static function UserGet ($userid){
    if ( isset($_SESSION['tusuarios'][$userid])){
        return $_SESSION['tusuarios'][$userid];
    }
    return null;
}

// Vuelca los datos al fichero
public static function UserSave(){
    
    $datosjon = json_encode($_SESSION['tusuarios']);
    file_put_contents(FILEUSER, $datosjon) or die ("Error al escribir en el fichero.");
}

}