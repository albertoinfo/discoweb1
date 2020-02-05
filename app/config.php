<?php


define ('GESTIONUSUARIOS','1');
define ('GESTIONFICHEROS','2');

// TAMAÑO MÁXIMO DEL FICHERO 2 MB
define ('TAMMAXIMOFILE',  2000000); 

// Fichero donde se guardan los datos
define('FILEUSER','app/dat/usuarios.json');
// Ruta donde se guardan los archivos de los usuarios
// Tiene que tener permiso 777 o permitir a Apache rwx
define('RUTA_FICHEROS','app/dat');
//  Estado: (A-Activo | B-Bloqueado |I-Inactivo )
const  ESTADOS = ['A' => 'Activo','B' =>'Bloqueado', 'I' => 'Inactivo']; 


// (0-Básico |1-Profesional |2- Premium| 3- Máster)
const  PLANES = ['Básico','Profesional','Premium','Máster'];
const LIMITE_FICHEROS = [50,100,200,0];
const LIMITE_ESPACIO  = [10000,20000,50000,0];

// Mensajes de la aplicación (Fácil de modificar o traducir)
const TMENSAJES = [
    'USREXIST' => "El ID del usuario ya existe",
    'USRERROR' => "El ID de usuario solo puede tener letras y números",
    'PASSDIST' => "Los valores de la contraseñas no son iguales",
    'PASSEASY' => "La contraseña no es segura",
    'MAILERROR' => "El correo electrónico no es correcto",
    'MAILREPE'  => "La dirección de correo ya está repetida",
    'NOVACIO'   => "Rellenar todos los campos",
];

    
// Definir otras constantes 