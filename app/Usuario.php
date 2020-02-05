<?php
namespace midiscowebv1\app;
/* DATOS DE USUARIO
 • Identificador ( 5 a 10 caracteres, no debe existir previamente, solo letras y números)
 • Contraseña ( 8 a 15 caracteres, debe ser segura)
 • Nombre ( Nombre y apellidos del usuario
 • Correo electrónico ( Valor válido de dirección correo, no debe existir previamente)
 • Tipo de Plan (0-Básico |1-Profesional |2- Premium| 3- Máster)
 • Estado: (A-Activo | B-Bloqueado |I-Inactivo )
 */

class Usuario
{
   private $identificador;
   private $clave;
   private $nombre;
   private $plan;
   private $estado;
   
   public function __construct($id,$key,$nom,$plan,$estado){
       $this->identificador = $id;
       $this->clave = $key;
       $this->nombre = $nom;
       $this->plan = $plan;
       $this->estado = $estado;
       
   }
   public function __construct(){
   
   }
}

