<?php
return [
 "permissions" => array(
   "Usuarios"=>array(
       "user.index"=>"Listar Usuarios",
       "user.create"=>"Crear Usuario",
       "user.store"=>"Guardar Usuario",
       "user.show"=>"Mostrar Usuario",
       "user.edit"=>"Editar Usuario",
       "user.update"=>"Actualizar Usuario",
       "user.perfil"=>"Ver Perfil",
       "user.change_password"=>"Actualizar Contraseña",
       "user.subir_foto"=>"Actualizar Foto Perfil",
       "user.permisos"=>"Actualizar Permisos",
       "user.destroy"=>"Eliminar Usuario",
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
     "equipos.create_mant_prev"=>"Crear Ticket Mantenimiento Preventivo",
     "equipos.create_tecnical_support"=>"Crear Ticket Soporte Tecnico",
     "equipos.detail"=>"Ver Detalle Equipo",
  ),

)];
