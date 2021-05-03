<?php
return [
 "permissions" => array(
   "Usuarios"=>array(
       "usuarios.index"=>"Listar Usuarios",
       "usuarios.create"=>"Crear Usuario",
       "usuarios.import"=>"Importar Usuarios",
       "usuarios.store"=>"Guardar Usuario",
       "usuarios.detail"=>"Mostrar Usuario",
       "usuarios.update"=>"Actualizar Usuario",
       "usuarios.profile"=>"Ver Perfil",
   ),
   "Roles"=>array(
       "role.index"=>"Listar Roles",
       "role.create"=>"Crear Rol",
       "role.store"=>"Guardar Rol",
       "role.show"=>"Mostrar Rol",
       "role.edit"=>"Editar Rol",
       "role.update"=>"Actualizar Rol",
   ),
   "Equipos"=>array(
     "equipos.index"=>"Listar Equipos",
     "equipos.create"=>"Crear Equipo",
     "equipos.create_daily_check"=>"Crear Daily Check",
     "equipos.edit_daily_check"=>"Actualizar Daily Check",
     "equipos.create_mant_prev"=>"Crear Ticket Mantenimiento Preventivo",
     "equipos.edit_mant_prev"=>"Actualizar Ticket Mantenimiento Preventivo",
     "equipos.create_tecnical_support"=>"Crear Ticket Soporte Tecnico",
     "equipos.detail"=>"Ver Detalle Equipo",
   ),
   "Taller"=>array(
    "taller"=>"Permisos personal de MORESA",
   ),
   "Baterias"=>array(
     "baterias.index"=>"Listar Baterias",
     "baterias.detail"=>"Detalle Bateria",
     "baterias.register_in_and_out"=>"Registrar entrada y salida de cuarto de maquinas",
   ),
   "Flujos Formulario"=>array(
     "parteA"=>"Primera Parte del proceso (creación)",
     "parteB"=>"Segunda Parte del proceso (edición)",
     "parteC"=>"Primera Parte 2 del proceso (creación2)",
   ),
 )];
