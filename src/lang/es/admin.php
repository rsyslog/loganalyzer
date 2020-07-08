<?php
/*
	*********************************************************************
	* Adiscon LogAnalyzer Version 4.1.10
	* ----------------------------------
	*
	* Copyright © 2020  Javier Pastor (aka VSC55)
	* <jpastor at cerebelum dot net>
	* https://github.com/vsc55/docker-loganalyzer
	* 
	* This program is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	* 
	* This program is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	* 
	* You should have received a copy of the GNU General Public License
	* along with this program.  If not, see <http://www.gnu.org/licenses/>.
	*********************************************************************
*/
global $content;

// Global Stuff
$content['LN_ADMINMENU_HOMEPAGE'] = "Volver a Mostrar Eventos";
$content['LN_ADMINMENU_GENOPT'] = "Preferencias";
$content['LN_ADMINMENU_SOURCEOPT'] = "Fuentes";
$content['LN_ADMINMENU_VIEWSOPT'] = "Vistas";
$content['LN_ADMINMENU_SEARCHOPT'] = "Búsquedas";
$content['LN_ADMINMENU_USEROPT'] = "Usuarios";
$content['LN_ADMINMENU_GROUPOPT'] = "Grupos";
$content['LN_ADMINMENU_CHARTOPT'] = "Gráficos";
$content['LN_ADMINMENU_FIELDOPT'] = "Campos";
$content['LN_ADMINMENU_DBMAPPINGOPT'] = "Mapeos DB";
$content['LN_ADMINMENU_MSGPARSERSOPT'] = "Analizadores de Mensajes";
$content['LN_ADMINMENU_REEPORTSOPT'] = "Informes";
$content['LN_ADMIN_CENTER'] = "Centro de Administración";
$content['LN_ADMIN_UNKNOWNSTATE'] = "Estado Desconocido";
$content['LN_ADMIN_ERROR_NOTALLOWED'] = "No está permitido acceder a esta página con su nivel de usuario.";
$content['LN_DELETEYES'] = "Si";
$content['LN_DELETENO'] = "No";
$content['LN_GEN_ACTIONS'] = "Acciones Disponibles";
$content['LN_ADMIN_SEND'] = "Enviar Cambios";
$content['LN_GEN_USERONLY'] = "Solo Usuario";
$content['LN_GEN_USERONLYNAME'] = "Usuario '%1'";
$content['LN_GEN_GROUPONLY'] = "Solo Grupo";
$content['LN_GEN_GLOBAL'] = "Global";
$content['LN_GEN_USERONLY_LONG'] = "Solo para mí <br>(Solo disponible para tu usuario)";
$content['LN_GEN_GROUPONLY_LONG'] = "Para este grupo <br>(Solo disponible para el grupo seleccionado)";
$content['LN_GEN_GROUPONLYNAME'] = "Grupo '%1'";
$content['LN_ADMIN_POPUPHELP'] = "Detalles sobre esta función";
$content['LN_ADMIN_DBSTATS'] = "Mostrar estadísticas de la base de datos.";
$content['LN_ADMIN_CLEARDATA'] = "Si necesita eliminar registros de datos antiguos, use esta función.";
$content['LN_UPDATE_AVAILABLE'] = "Actualización disponible";
$content['LN_UPDATE_INSTALLEDVER'] = "Versión instalada: ";
$content['LN_UPDATE_AVAILABLEVER'] = "Versión disponible: ";
$content['LN_UPDATE_LINK'] = "Haga clic aquí para obtener la actualización.";
$content['LN_ADMIN_RESULTREDIRECT'] = "Serás redirigido a <A HREF='%1'>esta página</A> en %2 segundos.";
$content['LN_ADMIN_RESULTCLICK'] = "Haga clic <A HREF='%1'>aquí</A> para continuar.";
$content['LN_ADMIN_GOBACK'] = "Ir Atras";

// General Options
$content['LN_ADMIN_GLOBFRONTEND'] = "Opciones de Interfaz Global";
	$content['LN_ADMIN_USERFRONTEND'] = "Opciones de Interfaz de usuario específicas";
$content['LN_ADMIN_MISC'] = "Opciones Varias";
$content['LN_GEN_SHOWDEBUGMSG'] = "Mostrar mensajes Debug";
	$content['LN_GEN_DEBUGGRIDCOUNTER'] = "Mostrar Contador de Cuadrícula de Depuración";
$content['LN_GEN_SHOWPAGERENDERSTATS'] = "Mostrar Estadísticas de Procesamiento de Página";
$content['LN_GEN_ENABLEGZIP'] = "Habilitar Salida Comprimida GZIP";
$content['LN_GEN_DEBUGUSERLOGIN'] = "Debug Iniciar Sesión de Usuario";
$content['LN_GEN_WEBSTYLE'] = "Estilo predeterminado seleccionado";
$content['LN_GEN_SELLANGUAGE'] = "Idioma seleccionado por defecto";
$content['LN_GEN_PREPENDTITLE'] = "Anteponer esta cadena en el título";
$content['LN_GEN_USETODAY'] = "Usar Hoy y Ayer en campos de tiempo";
$content['LN_GEN_DETAILPOPUPS'] = "Use Popup para mostrar los detalles completos del mensaje";
$content['LN_GEN_MSGCHARLIMIT'] = "Límite de caracteres del mensaje en la vista principal";
$content['LN_GEN_STRCHARLIMIT'] = "Límite de visualización de caracteres para todos los campos de tipo texto";
$content['LN_GEN_ENTRIESPERPAGE'] = "Número de entradas por página.";
$content['LN_GEN_AUTORELOADSECONDS'] = "Habilitar recarga automática después de X segundos";
$content['LN_GEN_ADMINCHANGEWAITTIME'] = "Tiempo de Recarga en el Panel de Administración";
$content['LN_GEN_IPADRRESOLVE'] = "Resolver Direcciones IP usando DNS";
$content['LN_GEN_CUSTBTNCAPT'] = "Título de búsqueda personalizada";
$content['LN_GEN_CUSTBTNSRCH'] = "Texto de búsqueda personalizada";
$content['LN_GEN_SUCCESSFULLYSAVED'] = "Los valores de configuración se han guardado correctamente";
$content['LN_GEN_INTERNAL'] = "Interna";
$content['LN_GEN_DISABLED'] = "Función desactivada";
$content['LN_GEN_CONFIGFILE'] = "Archivo de Configuración";
$content['LN_GEN_ACCESSDENIED'] = "Acceso denegado a esta función";
$content['LN_GEN_DEFVIEWS'] = "Vista seleccionada por defecto";
$content['LN_GEN_DEFSOURCE'] = "Fuente seleccionada por defecto";
$content['LN_GEN_DEFFONT'] = "Fuente predeterminada";
$content['LN_GEN_DEFFONTSIZE'] = "Tamaño de fuente predeterminado";
$content['LN_GEN_SUPPRESSDUPMSG'] = "Suprime mensajes duplicados";
$content['LN_GEN_TREATFILTERSTRUE'] = "Tratar los filtros de campos no encontrados como verdaderos";
$content['LN_GEN_INLINESEARCHICONS'] = "Mostrar iconos de búsqueda en línea dentro de los campos";
$content['LN_GEN_OPTIONNAME'] = "Nombre de la opción";
$content['LN_GEN_GLOBALVALUE'] = "Valor Global";
$content['LN_GEN_PERSONALVALUE'] = "Valor Personal (Usuario)";
$content['LN_GEN_DISABLEUSEROPTIONS'] = "Haga clic aquí para deshabilitar las opciones personales";
$content['LN_GEN_ENABLEUSEROPTIONS'] = "Haga clic aquí para habilitar las opciones personales";
$content['LN_ADMIN_GLOBALONLY'] = "Solo Opciones Globales";
$content['LN_GEN_DEBUGTOSYSLOG'] = "Enviar Debug al servidor syslog local";
$content['LN_GEN_POPUPMENUTIMEOUT'] = "Tiempo de espera del menú emergente en milisegundos";
$content['LN_ADMIN_SCRIPTTIMEOUT'] = "PHP Script Timeout in seconds";
$content['LN_GEN_INJECTHTMLHEADER'] = "Inyecte este código html en el área &lt;head&gt;.";
$content['LN_GEN_INJECTBODYHEADER'] = "Inyecte este código html al comienzo del área &lt;body&gt;.";
$content['LN_GEN_INJECTBODYFOOTER'] = "Inyecte este código html al final del área &lt;body&gt;.";
$content['LN_ADMIN_PHPLOGCON_LOGOURL'] = "Opcional LogAnalyzer Logo URL. Deje en blanco para usar el predeterminado.";
$content['LN_ADMIN_ERROR_READONLY'] = "Este es un usuario SÓLO LECTURA, no puede realizar ninguna operación de cambio.";
$content['LN_ADMIN_ERROR_NOTALLOWEDTOEDIT'] = "No tiene permiso para editar este elemento de configuración.";
$content['LN_ADMIN_USEPROXYSERVER'] = "¡Deje en blanco si no desea usar un servidor proxy! Si se establece en un servidor proxy válido (por ejemplo, '127.0.0.1:8080'), LogAnalyzer usará este servidor para consultas remotas como la función de verificación de actualización.";
$content['LN_ADMIN_DEFAULTENCODING'] = "Codificación de caracteres predeterminada"; 
$content['LN_GEN_CONTEXTLINKS'] = "Habilitar Enlaces de Contenido (signos de interrogación)";
$content['LN_GEN_DISABLEADMINUSERS'] = "Deshabilitar el Panel de Administración para usuarios normales";

// User Center
$content['LN_USER_CENTER'] = "Opciones de Usuario";
$content['LN_USER_ID'] = "ID";
$content['LN_USER_NAME'] = "Nombre de Usuario";
$content['LN_USER_ADD'] = "Agregar Usuario";
$content['LN_USER_EDIT'] = "Editar Usuario";
$content['LN_USER_DELETE'] = "Borrar Usuario";
$content['LN_USER_PASSWORD1'] = "Contraseña";
$content['LN_USER_PASSWORD2'] = "Confirmar Contraseña";
$content['LN_USER_ERROR_IDNOTFOUND'] = "Error, no se encontró el Usuario con ID '%1'";
$content['LN_USER_ERROR_DONOTDELURSLF'] = "¡Error, no puedes BORRARTE!";
$content['LN_USER_ERROR_DELUSER'] = "¡La eliminación del Usuario con ID '%1' falló!";
$content['LN_USER_ERROR_INVALIDID'] = "Error, ID no válido, Usuario no encontrado";
$content['LN_USER_ERROR_HASBEENDEL'] = "¡El Usuario '%1' ha sido eliminado con éxito!";
$content['LN_USER_ERROR_USEREMPTY'] = "Error, el Nombre de Usuario estaba vacío";
$content['LN_USER_ERROR_USERNAMETAKEN'] = "¡Error, este Nombre de Usuario ya está en uso!";
$content['LN_USER_ERROR_PASSSHORT'] = "Error, la contraseña era corta o no coincidía";
$content['LN_USER_ERROR_HASBEENADDED'] = "El Usuario '%1' se agregó correctamente";
$content['LN_USER_ERROR_HASBEENEDIT'] = "El Usuario '%1' se ha editado correctamente";
$content['LN_USER_ISADMIN'] = "¿Es Administrador?";
$content['LN_USER_ADDEDIT'] = "Agregar/Editar Usuario";
$content['LN_USER_WARNREMOVEADMIN'] = "Está a punto de revocar sus propios privilegios administrativos. ¿Estás seguro de eliminar tu estado de administrador?";
$content['LN_USER_WARNDELETEUSER'] = "¿Está seguro de que desea eliminar el usuario '%1'? También se eliminarán todas sus configuraciones personales.";
$content['LN_USER_ERROR_INVALIDSESSIONS'] = "Sesión de Usuario no válida.";
$content['LN_USER_ERROR_SETTINGFLAG'] = "Error al configurar el indicador, ID no válida o Usuario no encontrado";
$content['LN_USER_WARNRADYONLYADMIN'] = "¡Está a punto de configurar su cuenta como de solo lectura! ¡Esto le impedirá cambiar cualquier configuración! ¿Estás seguro de que quieres continuar?";
$content['LN_USER_ISREADONLY'] = "¿Usuario de Solo Lectura?";
$content['LN_USER_SETISADMIN'] = "Cambiar estado Es Administrador";
$content['LN_USER_SETISREADONLY'] = "Cambiar estado de Solo Lectura";

// Group center
$content['LN_GROUP_CENTER'] = "Gestion de Grupos";
$content['LN_GROUP_ID'] = "ID";
$content['LN_GROUP_NAME'] = "Nombre del Grupo";
$content['LN_GROUP_DESCRIPTION'] = "Descripción del Grupo";
$content['LN_GROUP_TYPE'] = "Tipo de Grupo";
$content['LN_GROUP_ADD'] = "Añadir Grupo";
$content['LN_GROUP_EDIT'] = "Editar Grupo";
$content['LN_GROUP_DELETE'] = "Eliminar Grupo";
$content['LN_GROUP_NOGROUPS'] = "No se han agregado grupos todavía";
$content['LN_GROUP_ADDEDIT'] = "Agregar/Editar Grupo";
$content['LN_GROUP_ERROR_GROUPEMPTY'] = "El Nombre del Grupo no puede estar vacío.";
$content['LN_GROUP_ERROR_GROUPNAMETAKEN'] = "El Nombre del Grupo ya está en uso.";
$content['LN_GROUP_HASBEENADDED'] = "El Grupo '%1' se ha agregado correctamente.";
$content['LN_GROUP_ERROR_IDNOTFOUND'] = "No se pudo encontrar el Grupo con ID '%1'.";
$content['LN_GROUP_ERROR_HASBEENEDIT'] = "El Grupo '%1' se ha editado correctamente.";
$content['LN_GROUP_ERROR_INVALIDGROUP'] = "Error, ID no válido, Grupo no encontrado";
$content['LN_GROUP_WARNDELETEGROUP'] = "¿Está seguro de que desea eliminar el Grupo '%1'? Todos los ajustes de Grupo también se eliminarán.";
$content['LN_GROUP_ERROR_DELGROUP'] = "¡Falló la eliminación del Grupo con ID '%1'!";
$content['LN_GROUP_ERROR_HASBEENDEL'] = "¡El Grupo '%1' se ha eliminado con éxito!";
$content['LN_GROUP_MEMBERS'] = "Miembros del Grupo: ";
$content['LN_GROUP_ADDUSER'] = "Agregar Usuario al Grupo";
$content['LN_GROUP_ERROR_USERIDMISSING'] = "Falta el ID de usuario.";
$content['LN_GROUP_USERHASBEENADDEDGROUP'] = "El Usuario '%1' se ha agregado correctamente al Grupo '%2'";
$content['LN_GROUP_ERRORNOMOREUSERS'] = "No hay más Usuarios disponibles que se puedan agregar al Grupo '%1'";
$content['LN_GROUP_USER_ADD'] = "Agregar Usuario al Grupo";
$content['LN_GROUP_USERDELETE'] = "Eliminar un Usuario del Grupo";
$content['LN_GROUP_ERRORNOUSERSINGROUP'] = "No hay Usuarios para eliminar en el grupo '%1'";
$content['LN_GROUP_ERROR_REMUSERFROMGROUP'] = "No se pudo eliminar el Usuario '%1' del Grupo '%2'";
$content['LN_GROUP_USERHASBEENREMOVED'] = "El Usuario '%1' se ha eliminado correctamente del Grupo '%2'";
$content['LN_GROUP_'] = "";

// Custom Searches center
$content['LN_SEARCH_CENTER'] = "Búsquedas Personalizadas";
$content['LN_SEARCH_ADD'] = "Agregar nueva Búsqueda Personalizada";
$content['LN_SEARCH_ID'] = "ID";
$content['LN_SEARCH_NAME'] = "Nombre de Búsqueda";
$content['LN_SEARCH_QUERY'] = "Consulta de Busqueda";
$content['LN_SEARCH_TYPE'] = "Asignado a";
$content['LN_SEARCH_EDIT'] = "Editar Búsqueda Personalizada";
$content['LN_SEARCH_DELETE'] = "Eliminar Búsqueda Personalizada";
$content['LN_SEARCH_ADDEDIT'] = "Agregar/Editar una Búsqueda Personalizada";
$content['LN_SEARCH_SELGROUPENABLE'] = ">> Seleccione Grupo para Habilitar <<";
$content['LN_SEARCH_ERROR_DISPLAYNAMEEMPTY'] = "El Nombre de Búsqueda no puede estar vacío.";
$content['LN_SEARCH_ERROR_SEARCHQUERYEMPTY'] = "La Consulta de Búsqueda no puede estar vacía.";
$content['LN_SEARCH_HASBEENADDED'] = "La Búsqueda Personalizada '%1' se ha agregado correctamente.";
$content['LN_SEARCH_ERROR_IDNOTFOUND'] = "No se pudo encontrar la Búsqueda con ID '%1'.";
$content['LN_SEARCH_ERROR_INVALIDID'] = "ID de Búsqueda no válida.";
$content['LN_SEARCH_HASBEENEDIT'] = "La Búsqueda Personalizada '%1' se ha editado correctamente.";
$content['LN_SEARCH_WARNDELETESEARCH'] = "¿Está seguro de que desea eliminar la Búsqueda Personalizada '%1'? ¡Esto no se puede deshacer!";
$content['LN_SEARCH_ERROR_DELSEARCH'] = "¡Error al eliminar la Búsqueda Personalizada con el ID '%1'!";
$content['LN_SEARCH_ERROR_HASBEENDEL'] = "¡La Búsqueda Personalizada '%1' se ha eliminado correctamente!";
$content['LN_SEARCH_'] = "";

// Custom Views center
$content['LN_VIEWS_CENTER'] = "Opciones de Vistas";
$content['LN_VIEWS_ID'] = "ID";
$content['LN_VIEWS_NAME'] = "Nombre de Vista";
$content['LN_VIEWS_COLUMNS'] = "Columnas de Vista";
$content['LN_VIEWS_TYPE'] = "Asignado a";
$content['LN_VIEWS_ADD'] = "Agregar nueva Vista";
$content['LN_VIEWS_EDIT'] = "Editar Vista";
$content['LN_VIEWS_ERROR_IDNOTFOUND'] = "No se pudo encontrar una vista con ID '%1'.";
$content['LN_VIEWS_ERROR_INVALIDID'] = "La Vista con ID '%1' no es una Vista válida.";
$content['LN_VIEWS_WARNDELETEVIEW'] = "¿Está seguro de que desea eliminar la Vista '%1'? ¡Esto no se puede deshacer!";
$content['LN_VIEWS_ERROR_DELSEARCH'] = "¡Falló la eliminación de la Vista con ID '%1'!";
$content['LN_VIEWS_ERROR_HASBEENDEL'] = "¡La Vista '%1' se ha eliminado con éxito!";
$content['LN_VIEWS_ADDEDIT'] = "Agregar/Editar Vista";
$content['LN_VIEWS_COLUMNLIST'] = "Columnas Configuradas";
$content['LN_VIEWS_ADDCOLUMN'] = "Agregar Columna a la lista";
$content['LN_VIEWS_ERROR_DISPLAYNAMEEMPTY'] = "El Nombre de Vista no puede estar vacío";
$content['LN_VIEWS_COLUMN'] = "Columna";
$content['LN_VIEWS_COLUMN_REMOVE'] = "Eliminar Columna";
$content['LN_VIEWS_HASBEENADDED'] = "La Vista Personalizada '%1' se ha agregado correctamente.";
$content['LN_VIEWS_ERROR_NOCOLUMNS'] = "Debe agregar al menos una columna para agregar una nueva Vista Personalizada.";
$content['LN_VIEWS_HASBEENEDIT'] = "La Vista Personalizada '%1' se ha editado correctamente.";
$content['LN_VIEWS_'] = "";

// Custom DBMappings center
$content['LN_DBMP_CENTER'] = "Opciones de Mapeado de Base de Datos";
$content['LN_DBMP_ID'] = "ID";
$content['LN_DBMP_NAME'] = "Nombre de Mapeado de Base de Datos";
$content['LN_DBMP_DBMAPPINGS'] = "Mapeo de Bases de Datos";
$content['LN_DBMP_ADD'] = "Agregar Nuevo Mapeo de Base de Datos";
$content['LN_DBMP_EDIT'] = "Modificar Mapeo de Base de Datos";
$content['LN_DBMP_DELETE'] = "Eliminar Mapeo de Base de Datos";
$content['LN_DBMP_ERROR_IDNOTFOUND'] = "No se pudo encontrar una Mapeo de Base de Datos con ID '%1'.";
$content['LN_DBMP_ERROR_INVALIDID'] = "La Mapeo de Base de Datos con ID '%1' no es una Mapeo de Base de Datos válida.";
$content['LN_DBMP_WARNDELETEMAPPING'] = "¿Está seguro de que desea eliminar el Mapeo de Base de Datos '%1'? ¡Esto no se puede deshacer!";
$content['LN_DBMP_ERROR_DELSEARCH'] = "¡Falló la eliminación del Mapeo de Base de Datos con ID '%1'!";
$content['LN_DBMP_ERROR_HASBEENDEL'] = "¡El Mapeo de Base de Datos '%1' se ha eliminado con éxito!";
$content['LN_DBMP_ADDEDIT'] = "Agregar/Editar Mapeo de Base de Datos";
$content['LN_DBMP_DBMAPPINGSLIST'] = "Asignaciones Configuradas";
$content['LN_DBMP_ADDMAPPING'] = "Agregar Asignación de Campo a la lista";
$content['LN_DBMP_ERROR_DISPLAYNAMEEMPTY'] = "El Nombre de Mapeado de Base de Datos no puede estar vacío";
$content['LN_DBMP_MAPPING'] = "Asignación";
$content['LN_DBMP_MAPPING_REMOVE'] = "Eliminar Asignación";
$content['LN_DBMP_MAPPING_EDIT'] = "Editar Asignación";
$content['LN_DBMP_MAPPING_MOVEUP'] = "Mover Asignación Arriba";
$content['LN_DBMP_MAPPING_MOVEDOWN'] = "Mover Asignación Abajo";
$content['LN_DBMP_HASBEENADDED'] = "El Mapeo de Base de Datos personalizado '%1' se ha agregado correctamente.";
$content['LN_DBMP_ERROR_NOCOLUMNS'] = "Debe agregar al menos una columna para agregar un nuevo Mapeo de Base de Datos personalizado.";
$content['LN_DBMP_HASBEENEDIT'] = "El Mapeo de Base de Datos personalizado '%1' se ha editado correctamente.";
$content['LN_DBMP_ERROR_MISSINGFIELDNAME'] = "La Asignación del campo '%1' no está definida.";
$content['LN_SOURCES_FILTERSTRING'] = "Filtro de Búsqueda Personalizado";
$content['LN_SOURCES_FILTERSTRING_HELP'] = "Use la misma sintaxis que en el campo de búsqueda. Por ejemplo, si desea mostrar solo mensajes de 'servidor1', use este filtro de búsqueda: source:=server1";

// Custom Sources center
$content['LN_SOURCES_CENTER'] = "Opciones de Fuentes";
$content['LN_SOURCES_EDIT'] = "Editar Fuente";
$content['LN_SOURCES_DELETE'] = "Eliminar Fuente";
$content['LN_SOURCES_ID'] = "ID";
$content['LN_SOURCES_NAME'] = "Nombre de la Fuente";
$content['LN_SOURCES_TYPE'] = "Tipo de Fuente";
$content['LN_SOURCES_ASSIGNTO'] = "Asignado a";
$content['LN_SOURCES_DISK'] = "Archivo en Disco";
$content['LN_SOURCES_DB'] = "Base de Datos MySQL";
$content['LN_SOURCES_CLICKHOUSE'] = "Base de Datos ClickHouse";
$content['LN_SOURCES_PDO'] = "Fuente de datos PDO";
$content['LN_SOURCES_MONGODB'] = "Fuente de datos MongoDB";
$content['LN_SOURCES_ADD'] = "Agregar nueva Fuente";
$content['LN_SOURCES_ADDEDIT'] = "Agregar/Editar Fuente";
$content['LN_SOURCES_TYPE'] = "Tipo de Fuente";
$content['LN_SOURCES_DISKTYPEOPTIONS'] = "Opciones relacionadas con el archivo en disco";
$content['LN_SOURCES_ERROR_MISSINGPARAM'] = "Falta el parámetro '%1'.";
$content['LN_SOURCES_ERROR_NOTAVALIDFILE'] = "¡Error al abrir el archivo syslog '%1'! Compruebe si el archivo existe y LogAnalyzer tiene suficientes permisos.";
$content['LN_SOURCES_ERROR_UNKNOWNSOURCE'] = "Fuente desconocida '%1' detectada";
$content['LN_SOURCE_HASBEENADDED'] = "La nueva Fuente '%1' se ha agregado con éxito.";
$content['LN_SOURCES_EDIT'] = "Editar Fuente";
$content['LN_SOURCES_ERROR_INVALIDORNOTFOUNDID'] = "El ID de la Fuente no es válido o no se pudo encontrar.";
$content['LN_SOURCES_ERROR_IDNOTFOUND'] = "No se pudo encontrar el ID de la Fuente en la base de datos.";
$content['LN_SOURCES_HASBEENEDIT'] = "La Fuente '%1' se ha editado correctamente.";
$content['LN_SOURCES_WARNDELETESEARCH'] = "¿Está seguro de que desea eliminar la fuente '%1'? ¡Esto no se puede deshacer!";
$content['LN_SOURCES_ERROR_DELSOURCE'] = "¡Error al eliminar la Fuente con el ID '%1'!";
$content['LN_SOURCES_ERROR_HASBEENDEL'] = "¡La Fuente '%1' se ha eliminado con éxito!";
$content['LN_SOURCES_DESCRIPTION'] = "Descripción de la Fuente (opcional)";
$content['LN_SOURCES_ERROR_INVALIDVALUE'] = "Valor no válido para el parámetro '%1'.";
$content['LN_SOURCES_STATSNAME'] = "Nombre";
$content['LN_SOURCES_STATSVALUE'] = "Valor";
$content['LN_SOURCES_DETAILS'] = "Detalles para esta fuente de flujo de registro";
$content['LN_SOURCES_STATSDETAILS'] = "Detalles estadísticos para esta fuente de flujo de registro";
$content['LN_SOURCES_ERROR_NOSTATSDATA'] = "No se pudo encontrar ni obtener ninguna información relacionada con las estadísticas para esta fuente de flujo de registro.";
$content['LN_SOURCES_ERROR_NOCLEARSUPPORT'] = "Esta fuente de flujo de registro no admite la eliminación de datos.";
$content['LN_SOURCES_ROWCOUNT'] = "Recuento Total de Filas";
$content['LN_SOURCES_CLEARDATA'] = "Las siguientes opciones de mantenimiento de la base de datos están disponibles";
$content['LN_SOURCES_CLEAROPTIONS'] = "Seleccione cómo desea borrar los datos.";
$content['LN_SOURCES_CLEARALL'] = "Borrar todos los datos.";
$content['LN_SOURCES_CLEAR_HELPTEXT'] = "¡Atención! ¡Tenga cuidado con la eliminación de datos, cualquier acción realizada aquí no se puede deshacer!";
$content['LN_SOURCES_CLEARSINCE'] = "Borrar todos los datos anteriores a ... ";
$content['LN_SOURCES_CLEARDATE'] = "Borrar todos los datos que sean anteriores a la fecha ... ";
$content['LN_SOURCES_CLEARDATA_SEND'] = "Borrar el rango de datos seleccionado";
$content['LN_SOURCES_ERROR_INVALIDCLEANUP'] = "Tipo de limpieza de datos no válido";
$content['LN_SOURCES_WARNDELETEDATA'] = "¿Está seguro de que desea borrar los datos de registro en la Fuente '%1'? ¡Esto no se puede deshacer!";
$content['LN_SOURCES_ERROR_DELDATA'] = "No se pudieron eliminar datos en la Fuente '%1'";
$content['LN_SOURCES_HASBEENDELDATA'] = "Los datos se eliminaron correctamente de la Fuente '%1', las filas '%2' se vieron afectadas. ";

// Database Upgrade
$content['LN_DBUPGRADE_TITLE'] = "Actualización de la Base de Datos de LogAnalyzer";
$content['LN_DBUPGRADE_DBFILENOTFOUND'] = "¡No se pudo encontrar el archivo de actualización de la Base de Datos '%1' en la carpeta include! Compruebe si todos los archivos se cargaron correctamente.";
$content['LN_DBUPGRADE_DBDEFFILESHORT'] = "¡Los archivos de actualización de la Base de Datos estaban vacíos o no contenían ningún comando SQL! Compruebe si todos los archivos se cargaron correctamente.";
$content['LN_DBUPGRADE_WELCOME'] = "Bienvenido a la actualización de la base de datos";
$content['LN_DBUPGRADE_BEFORESTART'] = "Antes de comenzar a actualizar su Base de Datos, debe crear una <b>COPIA DE SEGURIDAD COMPLETA DE SU BASE DE DATOS</b>. Todo lo demás se hará automáticamente por el script de actualización.";
$content['LN_DBUPGRADE_CURRENTINSTALLED'] = "Versión actual de la Base de Datos instalada";
$content['LN_DBUPGRADE_TOBEINSTALLED'] = "Se instalará la versión de la Base de Datos";
$content['LN_DBUPGRADE_HASBEENDONE'] = "Se realizó la actualización de la Base de Datos, vea los resultados a continuación";
$content['LN_DBUPGRADE_SUCCESSEXEC'] = "Sentencia ejecutadas con éxito";
$content['LN_DBUPGRADE_FAILEDEXEC'] = "Sentencia fallidas";
$content['LN_DBUPGRADE_ONESTATEMENTFAILED'] = "Al menos una declaración falló, es posible que deba corregir y solucionar este problema manualmente. Ver detalles del error a continuación";
$content['LN_DBUPGRADE_ERRMSG'] = "Mensaje de Error";
$content['LN_DBUPGRADE_ULTRASTATSDBVERSION'] = "Versión de la Base de Datos de LogAnalyzer";

// Charts Options
$content['LN_CHARTS_CENTER'] = "Opciones de Gráficos";
$content['LN_CHARTS_EDIT'] = "Editar Gráfico";
$content['LN_CHARTS_DELETE'] = "Eliminar Gráfico";
$content['LN_CHARTS_ADD'] = "Agregar nuevo Gráfico";
$content['LN_CHARTS_ADDEDIT'] = "Agregar/Editar Gráfico";
$content['LN_CHARTS_NAME'] = "Nombre del Gráfico";
$content['LN_CHARTS_ENABLED'] = "Gráfico Habilitado";
$content['LN_CHARTS_ENABLEDONLY'] = "Habilitado";
$content['LN_CHARTS_ERROR_INVALIDORNOTFOUNDID'] = "La ID del Gráfico no es válida o no se pudo encontrar.";
$content['LN_CHARTS_WARNDELETESEARCH'] = "¿Está seguro de que desea eliminar el Gráfico '%1'? ¡Esto no se puede deshacer!";
$content['LN_CHARTS_ERROR_DELCHART'] = "¡Error al eliminar el Gráfico con ID '%1'!";
$content['LN_CHARTS_ERROR_HASBEENDEL'] = "¡El Gráfico '%1' se ha eliminado con éxito!";
$content['LN_CHARTS_ERROR_MISSINGPARAM'] = "Falta el parámetro '%1'.";
$content['LN_CHARTS_HASBEENADDED'] = "El nuevo Gráfico '%1' se ha agregado con éxito.";
$content['LN_CHARTS_ERROR_IDNOTFOUND'] = "No se pudo encontrar el ID de Gráfico en la Base de Datos.";
$content['LN_CHARTS_HASBEENEDIT'] = "El Gráfico '%1' se ha editado correctamente.";
$content['LN_CHARTS_ID'] = "ID";
$content['LN_CHARTS_ASSIGNTO'] = "Asignado a";
$content['LN_CHARTS_PREVIEW'] = "Vista previa del Gráfico en una nueva ventana";
$content['LN_CHARTS_FILTERSTRING'] = "Filtro Personalizado";
$content['LN_CHARTS_FILTERSTRING_HELP'] = "Use la misma sintaxis que en el campo de búsqueda. Por ejemplo, si desea generar un gráfico para 'servidor1', use este filtro: source:=server1";
$content['LN_CHARTS_ERROR_CHARTIDNOTFOUND'] = "Error, no se encontró el Gráfico con ID '%1'";
	$content['LN_CHARTS_ERROR_SETTINGFLAG'] = "Error setting flag, invalid ChartID or operation.";
$content['LN_CHARTS_SETENABLEDSTATE'] = "Cambiar estado de Habilitado";

// Fields Options
$content['LN_FIELDS_CENTER'] = "Opciones de Campos";
$content['LN_FIELDS_EDIT'] = "Editar Campo";
$content['LN_FIELDS_DELETE'] = "Eliminar Campo";
$content['LN_FIELDS_ADD'] = "Agregar Nuevo Campo";
$content['LN_FIELDS_ID'] = "ID";
$content['LN_FIELDS_NAME'] = "Nombre del Campo";
$content['LN_FIELDS_DEFINE'] = "ID Interno";
$content['LN_FIELDS_DELETE_FROMDB'] = "Eliminar Campo de la Base de Datos";
$content['LN_FIELDS_ADDEDIT'] = "Agregar/Editar Campo";
$content['LN_FIELDS_TYPE'] = "Tipo de Campo";
$content['LN_FIELDS_ALIGN'] = "Alineación en Listado";
$content['LN_FIELDS_SEARCHONLINE'] = "Habilitar Búsqueda en Línea";
$content['LN_FIELDS_DEFAULTWIDTH'] = "Ancho de Fila en Listado";
$content['LN_FIELDS_ERROR_IDNOTFOUND'] = "El ID no se pudo encontrar en la Base de Datos o en las constantes predeterminadas.";
$content['LN_FIELDS_ERROR_INVALIDID'] = "El Campo con ID '%1' no es un campo válido.";
$content['LN_FIELDS_SEARCHFIELD'] = "Nombre del Filtro de Búsqueda";
$content['LN_FIELDS_WARNDELETESEARCH'] = "¿Está seguro de que desea eliminar el Campo '%1'? ¡Esto no se puede deshacer!";
$content['LN_FIELDS_ERROR_DELSEARCH'] = "The Field-ID could not be found in the database.";
$content['LN_FIELDS_ERROR_HASBEENDEL'] = "¡El Campo '%1' se ha eliminado con éxito!";
$content['LN_FIELDS_ERROR_FIELDCAPTIONEMPTY'] = "El Título del Campo estaba vacío. ";
$content['LN_FIELDS_ERROR_FIELDIDEMPTY'] = "La ID del Campo estaba vacía. ";
$content['LN_FIELDS_ERROR_SEARCHFIELDEMPTY'] = "El Filtro de Búsqueda estaba vacío. ";
$content['LN_FIELDS_ERROR_FIELDDEFINEEMPTY'] = "El ID Interno estaba vacío. ";
$content['LN_FIELDS_HASBEENEDIT'] = "La configuración para el Campo '%1' se ha editado correctamente.";
$content['LN_FIELDS_HASBEENADDED'] = "La configuración para el Campo '%1' se ha agregado con éxito.";
$content['LN_FIELDS_'] = "";
$content['LN_ALIGN_CENTER'] = "centrar";
$content['LN_ALIGN_LEFT'] = "izquierda";
$content['LN_ALIGN_RIGHT'] = "derecha";
$content['LN_FILTER_TYPE_STRING'] = "Texto";
$content['LN_FILTER_TYPE_NUMBER'] = "Numero";
$content['LN_FILTER_TYPE_DATE'] = "Fecha";

// Parser Options
$content['LN_PARSERS_EDIT'] = "Editar Analizador de Mensajes";
$content['LN_PARSERS_DELETE'] = "Eliminar Analizador de Mensajes";
$content['LN_PARSERS_ID'] = "ID";
$content['LN_PARSERS_NAME'] = "Nombre";
$content['LN_PARSERS_DESCRIPTION'] = "Descripción";
$content['LN_PARSERS_ERROR_NOPARSERS'] = "No se encontraron analizadores de mensajes válidos en su instalación.";
$content['LN_PARSERS_HELP'] = "Ayuda";
$content['LN_PARSERS_HELP_CLICK'] = "Haga clic aquí para obtener una descripción detallada";
$content['LN_PARSERS_INFO'] = "Mostrar más información de este Analizador de Mensajes.";
$content['LN_PARSERS_INIT'] = "Inicialice la configuración de este Analizador de Mensajes.";
$content['LN_PARSERS_REMOVE'] = "Elimine la configuración de este Analizador de Mensajes.";
$content['LN_PARSERS_ERROR_IDNOTFOUND'] = "No se encontró un Analizador de Mensajes con el ID '%1'.";
$content['LN_PARSERS_ERROR_INVALIDID'] = "ID de Analizador de Mensaje no válido";
$content['LN_PARSERS_DETAILS'] = "Detalles de este Analizador";
$content['LN_PARSERS_CUSTOMFIELDS'] = "Este Analizador de Mensajes necesita los siguientes Campos Personalizados.";
$content['LN_PARSERS_WARNREMOVE'] = "Está a punto de eliminar los campos personalizados que necesita el Analizador de Mensajes '%1'. Sin embargo, puede agregar estos campos nuevamente si cambia de opinión.";
$content['LN_PARSERS_ERROR_HASBEENREMOVED'] = "Se han eliminado todas las configuraciones ('%2' campos personalizados) para el Analizador de Mensajes '%1'.";
$content['LN_PARSERS_ERROR_HASBEENADDED'] = "Se han agregado todas las configuraciones requeridas ('%2' campos personalizados) para el Analizador de Mensajes '%1'.";
$content['LN_PARSERS_ERROR_NOFIELDS'] = "El Analizador de Mensajes '%1' no tiene campos personalizados para agregar.";
$content['LN_PARSERSMENU_LIST'] = "Lista de Analizadores de Mensajes Instalados";
$content['LN_PARSERS_ONLINELIST'] = "Todos los Analizadores Disponibles";
$content['LN_PARSERS_'] = "";

// Command Line stuff
$content['LN_CMD_NOOP'] = "Falta el parámetro de operación";
$content['LN_CMD_NOLOGSTREAM'] = "Falta el parámetro fuente de flujo de registro";
$content['LN_CMD_LOGSTREAMNOTFOUND'] = "¡No se pudo encontrar la fuente de flujo de registro con ID '%1' en la base de datos!";
$content['LN_CMD_COULDNOTGETROWCOUNT'] = "No se pudo obtener el recuento de filas de la fuente de flujo de registro '%1'";
$content['LN_CMD_SUBPARAM1MISSING'] = "Falta el subparámetro 1, debe establecerse en 'all', 'since' o 'date'. Para más detalles ver la documentación.";
$content['LN_CMD_WRONGSUBOPORMISSING'] = "O la suboperación es incorrecta o falta otro parámetro";
$content['LN_CMD_FAILEDTOCLEANDATA'] = "Error al limpiar datos para el flujo de registro '%1'.";
$content['LN_CMD_CLEANINGDATAFOR'] = "Datos de limpieza para la fuente de flujo de registro '%1'.";
$content['LN_CMD_ROWSFOUND'] = "Conexión realizada con éxito y encontradas '%1' filas en la fuente de flujo de registro.";
$content['LN_CMD_DELETINGOLDERTHEN'] = "Realizar la eliminación de entradas de datos anteriores a '%1'.";
$content['LN_CMD_DELETEDROWS'] = "Se eliminaron correctamente '%1' filas en la fuente de flujo de registro.";
$content['LN_CMD_'] = "";

// Report Options
$content['LN_REPORTS_EDIT'] = "Editar Informe";
$content['LN_REPORTS_DELETE'] = "Eliminar Informe";
$content['LN_REPORTS_REQUIREDFIELDS'] = "Campos Requeridos";
$content['LN_REPORTS_ERROR_NOREPORTS'] = "No se encontraron Informes válidos en su instalación.";
$content['LN_REPORTS_INIT'] = "Inicializar configuraciones";
$content['LN_REPORTS_REMOVE'] = "Eliminar configuraciones";
$content['LN_REPORTS_ERROR_IDNOTFOUND'] = "No se encontró ningún Informe con el ID '%1'.";
$content['LN_REPORTS_ERROR_INVALIDID'] = "ID de Informe no válido.";
$content['LN_REPORTS_DETAILS'] = "Detalles para este Informe";
$content['LN_REPORTS_WARNREMOVE'] = "Está a punto de eliminar la configuración personalizada que necesita el Informe '%1'. Sin embargo, puede agregar estas configuraciones nuevamente si cambia de opinión.";
$content['LN_REPORTS_ERROR_HASBEENREMOVED'] = "Se han eliminado todas las configuraciones para el Informe '%1'.";
$content['LN_REPORTS_ERROR_HASBEENADDED'] = "All required settings for the report '%1' have been added.";
$content['LN_REPORTS_ERROR_NOFIELDS'] = "El Informe '%1' no tiene ninguna configuración personalizada que se pueda agregar.";
$content['LN_REPORTS_ERROR_REPORTDOESNTNEEDTOBEREMOVED'] = "El Informe '%1' no necesita ser eliminado o inicializado.";
$content['LN_REPORTS_REMOVESAVEDREPORT'] = "Eliminar Informe Guardado";
$content['LN_REPORTS_CUSTOMTITLE'] = "Título del Informe";
$content['LN_REPORTS_CUSTOMCOMMENT'] = "Comentario/Descripción";
$content['LN_REPORTS_FILTERSTRING'] = "Cadena de Filtro";
$content['LN_REPORTS_OUTPUTFORMAT'] = "Formato de Salida";
$content['LN_REPORTS_OUTPUTTARGET'] = "Apuntar Salida";
$content['LN_REPORTS_HASBEENADDED'] = "El Informe '%1' se ha agregado correctamente.";
$content['LN_REPORTS_HASBEENEDIT'] = "El Informe '%1' se ha editado correctamente.";
$content['LN_REPORTS_SOURCEID'] = "Fuente de Flujo de Registro";
$content['LN_REPORTS_ERROR_SAVEDREPORTIDNOTFOUND'] = "No se encontró ningún Informe con ID '%1'.";
$content['LN_REPORTS_ERROR_INVALIDSAVEDREPORTID'] = "El Informe no es válido.";
$content['LN_REPORTS_WARNDELETESAVEDREPORT'] = "¿Está seguro de que desea eliminar el Informe '%1'?";
$content['LN_REPORTS_ERROR_DELSAVEDREPORT'] = "¡Error al eliminar el Informe con ID '%1'!";
$content['LN_REPORTS_ERROR_HASBEENDEL'] = "¡El Informe '%1' se ha eliminado correctamente!";
$content['LN_REPORTS_FILTERLIST'] = "Lista de Filtros";
$content['LN_REPORTS_FILTER'] = "Filtro";
$content['LN_REPORTS_ADDFILTER'] = "Añadir Filtro";
$content['LN_REPORTS_FILTER_EDIT'] = "Editar Filtro";
$content['LN_REPORTS_FILTER_MOVEUP'] = "Mueve Filtro Arriba";
$content['LN_REPORTS_FILTER_MOVEDOWN'] = "Mueve Filtro Abajo";
$content['LN_REPORTS_FILTER_REMOVE'] = "Eliminar Filtro";
$content['LN_REPORTS_FILTEREDITOR'] = "Editor de Filtros";
$content['LN_REPORTS_FILTERSTRING_ONLYEDITIF'] = "¡Solo edite la cadena de filtro sin formato si sabe lo que está haciendo! Tenga en cuenta que si cambia la cadena de filtro, se perderán los cambios realizados en el editor de filtros.";
$content['LN_REPORTS_ADVANCEDFILTERS'] = "Filtros Avanzados";
	$content['LN_REPORTS_ADVANCEDFILTERLIST'] = "Lista de filtros de informes avanzados";
	$content['LN_REPORTS_OUTPUTTARGET_DETAILS'] = "Outputtarget Options";
$content['LN_REPORTS_OUTPUTTARGET_FILE'] = "Ruta de Salida y Nombre de Archivo";
$content['LN_REPORTS_CRONCMD'] = "Comando Local para Generar Informe";
$content['LN_REPORTS_LINKS'] = "Enlaces Relacionados";
$content['LN_REPORTS_INSTALLED'] = "Instalado";
$content['LN_REPORTS_NOTINSTALLED'] = "No instalado";
$content['LN_REPORTS_DOWNLOAD'] = "Enlace de Descarga";
$content['LN_REPORTS_SAMPLELINK'] = "Informe de Muestra";
$content['LN_REPORTS_DETAILSFOR'] = "Detalles para el Informe '%1'";
$content['LN_REPORTS_PERFORMANCE_WARNING'] = "Advertencia de Rendimiento de Flujo de Registro";
$content['LN_REPORTS_OPTIMIZE_LOGSTREAMSOURCE'] = "Sí, ¡Optimice la fuente de flujo de registro!";
$content['LN_REPORTS_OPTIMIZE_INDEXES'] = "La fuente de datos '%1' no está optimizada para este informe. Falta al menos un ÍNDICE. Crear ÍNDICES acelerará la generación de informes.<br>
<br>
¿Desea que LogAnalyzer cree los ÍNDICES necesarios ahora? Esto puede llevar más de unos minutos, ¡así que tenga paciencia!";
$content['LN_REPORTS_ERROR_FAILED_CREATE_INDEXES'] = "Error al crear ÍNDICES para el origen de datos '%1' con el código de error '%2'";
$content['LN_REPORTS_INDEX_CREATED'] = "ÍNDICES de flujo de registros creados";
$content['LN_REPORTS_INDEX_CREATED_SUCCESS'] = "Se crearon con éxito todos los ÍNDICES para la fuente de datos '%1'.";
$content['LN_REPORTS_OPTIMIZE_TRIGGER'] = "La fuente de datos '%1' no tiene un TRIGGER instalado para generar automáticamente la suma de verificación del mensaje en INSERT. Crear el TRIGGER acelerará la generación de informes.<br>
<br>
¿Desea que LogAnalyzer cree el TRIGGER ahora?";
$content['LN_REPORTS_TRIGGER_CREATED'] = "TRIGGER para el flujo de registro creado";
$content['LN_REPORTS_TRIGGER_CREATED_SUCCESS'] = "TRIGGER creado con éxito para la fuente de datos '%1'.";
$content['LN_REPORTS_ERROR_FAILED_CREATE_TRIGGER'] = "Error al crear TRIGGER para la fuente de datos '%1' con el código de error '%2'";
$content['LN_REPORTS_CHANGE_CHECKSUM'] = "El campo CHECKSUM para el origen de datos '%1' no está establecido en UNSIGNED INT. Para que el informe funcione correctamente, ¡es necesario cambiar el campo CHECKSUM a UNSIGNED INT!<br>
<br>
¿Desea que LogAnalyzer cambie el campo CHECKSUM ahora? Esto puede llevar más de unos minutos, ¡así que tenga paciencia!";
$content['LN_REPORTS_ERROR_FAILED_CHANGE_CHECKSUM'] = "Error al cambiar el campo CHECKSUM para el origen de datos '%1' con el código de error '%2'";
$content['LN_REPORTS_CHECKSUM_CHANGED'] = "El campo CHECKSUM cambió";
$content['LN_REPORTS_CHECKSUM_CHANGED_SUCCESS'] = "Cambió correctamente del campo CHECKSUM para el origen de datos '%1'.";
$content['LN_REPORTS_LOGSTREAM_WARNING'] = "Advertencia de Flujo de Registro";
$content['LN_REPORTS_ADD_MISSINGFIELDS'] = "La fuente de datos '%1' no contiene todos los campos de datos necesarios. Falta al menos un CAMPO.<br>
<br>
¿Desea que LogAnalyzer cree los campos de datos faltantes ahora?";
$content['LN_REPORTS_ERROR_FAILED_ADDING_FIELDS'] = "Error al agregar los campos que faltan en la fuente de datos '%1' con el código de error '%2'";
	$content['LN_REPORTS_FIELDS_CREATED'] = "Se agregaron los campos que faltaban";
$content['LN_REPORTS_FIELDS_CREATED_SUCCESS'] = "Se agregaron correctamente los campos que faltaban para la fuente de datos '%1'.";
$content['LN_REPORTS_RECHECKLOGSTREAMSOURCE'] = "¿Desea verificar la fuente actual de flujo de registro nuevamente?";
$content['LN_REPORTS_ADDSAVEDREPORT'] = "Agregar Informe y Guardar Cambios";
$content['LN_REPORTS_EDITSAVEDREPORT'] = "Guardar Cambios";
$content['LN_REPORTS_ADDSAVEDREPORTANDRETURN'] = "Agregar Informe y volver a la lista de Informes";
$content['LN_REPORTS_EDITSAVEDREPORTANDRETURN'] = "Guardar cambios y volver a la lista de Informes";
$content['LN_REPORTS_SOURCE_WARNING'] = "Advertencia de Origen de Flujo de registro";	
$content['LN_REPORTS_ERROR_FAILED_SOURCE_CHECK'] = "Error al verificar el origen de datos '%1' con el error '%2'";
$content['LN_REPORTS_'] = "";


?>
