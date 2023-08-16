<?php
$permisos["permissions"] = array(
  "Dashboard"=>array(
    "dashboard.gmp"=>"Ver dashboard",
  ),
  "Usuarios"=>array(
      "usuarios.index"=>"Listar Usuarios",
      "usuarios.create"=>"Crear Usuario",
      "usuarios.import"=>"Importar Usuarios",
      "usuarios.store"=>"Guardar Usuario",
      "usuarios.detail"=>"Mostrar Usuario",
      "usuarios.update"=>"Actualizar Usuario",
      "usuarios.profile"=>"Ver Perfil",
      "usuarios.editar_rol"=>"Editar Rol",
      "usuarios.delete"=>"Desactivar Usuario",
      "usuarios.password"=>"Modificar password",
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
    "equipos.see_daily_check"=>"Ver Daily Check",
    "equipos.create_daily_check"=>"Crear Daily Check",
    "equipos.edit_daily_check"=>"Actualizar Daily Check",
    "equipos.delete_daily_check"=>"Borrar registro Daily Check",
    "equipos.create_mant_prev"=>"Crear Ticket Mantenimiento Preventivo",
    "equipos.see_mant_prev"=>"Ver Ticket Mantenimiento Preventivo",
    "equipos.edit_mant_prev"=>"Actualizar Ticket Mantenimiento Preventivo",
    "equipos.delete_mant_prev"=>"Borrar Ticket Mantenimiento Preventivo",
    "equipos.see_tecnical_support"=>"Ver Servicio Soporte Técnico",
    "equipos.create_tecnical_support"=>"Crear Servicio Soporte Técnico",
    "equipos.create_tecnical_support_prefilled"=>"Crear Servicio Soporte Técnico desde daily check",
    "equipos.assign_tecnical_support"=>"Asignar Servicio de Soporte Técnico",
    "equipos.start_tecnical_support"=>"Iniciar Servicio de Soporte Técnico",
    "equipos.edit_tecnical_support"=>"Terminar Servicio Soporte Técnico",
    "equipos.delete_tecnical_support"=>"Borrar Servicio Soporte Técnico",
    "equipos.create_control_entrega"=>"Crear Control de entregas Alquiler",
    "equipos.edit_control_entrega"=>"Editar Control de entregas Alquiler",
    "equipos.show_control_entrega"=>"Ver Control de entregas Alquiler",
    "equipos.delete_control_entrega"=>"Eliminar Control de entregas Alquiler",
    "equipos.detail"=>"Ver Detalle Equipo",
    "equipos.calendar"=>"Ver Calendario (Servicio Técnico)",
    "equipos.dominio"=>"Ver filtro de dominio",
    "equipos.historial"=>"Ver historial de reportes",
   
  ),
  "Taller"=>array(
   "taller"=>"Permisos personal de MORESA",
  ),
  "Baterias"=>array(
    "baterias.index"=>"Listar Baterias",
    "baterias.detail"=>"Detalle Bateria",
    "baterias.register_in_and_out"=>"Registrar entrada y salida de cuarto de maquinas",
    "baterias.serv_tec"=>"Ver servicio tecnico",
    "baterias.serv_tec_store"=>"Registrar servicio tecnico",
    "baterias.serv_tec_update"=>"Firmar servicio tecnico",
    "baterias.hidratacion"=>"Ver hidratacion",
    "baterias.register_hidratacion"=>"Crear hidratacion",
    "baterias.store_hidratacion"=>"Guardar hidratacion",
  ),
  "Flujos Formulario"=>array(
    "parteA"=>"Primera Parte del proceso (creación)",
    "parteB"=>"Segunda Parte del proceso (edición)",
    "parteC"=>"Firma del cliente",
  ),
  "Flujos soporte Tecnico"=>array(
    "sp.parteA"=>"Primera Parte del proceso (creación)",
    "sp.parteB"=>"Segunda Parte del proceso (edicion hora entrada)",
    "sp.parteC"=>"Tercera Parte 2 del proceso (edicion hora salida)",
  ),
  
 
);

if(env('APP_DEBUG')=='true'){
    $permisos["permissions"]["Formularios"]=array(
      "formularios.index"=>"Formularios index",
      "formularios.create"=>"Formularios create",
      "formularios.edit"=>"Formularios edit",
      "formularios.store"=>"Formularios store",
      "formularios.show"=>"Formularios show",
      "formularios.update"=>"Formularios update"
    );
  }
return $permisos;