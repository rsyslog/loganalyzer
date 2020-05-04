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
	$content['LN_ADMINMENU_VIEWSOPT'] = "Views";
$content['LN_ADMINMENU_SEARCHOPT'] = "Búsquedas";
$content['LN_ADMINMENU_USEROPT'] = "Usuarios";
$content['LN_ADMINMENU_GROUPOPT'] = "Grupos";
$content['LN_ADMINMENU_CHARTOPT'] = "Gráficos";
$content['LN_ADMINMENU_FIELDOPT'] = "Campos";
	$content['LN_ADMINMENU_DBMAPPINGOPT'] = "DBMappings";
$content['LN_ADMINMENU_MSGPARSERSOPT'] = "Analizadores de Mensajes";
$content['LN_ADMINMENU_REEPORTSOPT'] = "Módulos de Informes";
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
	$content['LN_GEN_INTERNAL'] = "Internal";
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
	$content['LN_USER_SETISADMIN'] = "Toggle IsAdmin State";
	$content['LN_USER_SETISREADONLY'] = "Toggle IsReadOnly State";

// Group center
	$content['LN_GROUP_CENTER'] = "Group Center";
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
	$content['LN_GROUP_ERRORNOUSERSINGROUP'] = "No hay Usuarios para eliminar en este grupo '%1'";
$content['LN_GROUP_ERROR_REMUSERFROMGROUP'] = "No se pudo eliminar el Usuario '%1' del Grupo '%2'";
$content['LN_GROUP_USERHASBEENREMOVED'] = "El Usuario '%1' se ha eliminado correctamente del Grupo '%2'";
$content['LN_GROUP_'] = "";

// Custom Searches center
	$content['LN_SEARCH_CENTER'] = "Custom Searches";
	$content['LN_SEARCH_ADD'] = "Add new Custom Search";
	$content['LN_SEARCH_ID'] = "ID";
	$content['LN_SEARCH_NAME'] = "Search Name";
	$content['LN_SEARCH_QUERY'] = "Search Query";
	$content['LN_SEARCH_TYPE'] = "Assigned to";
	$content['LN_SEARCH_EDIT'] = "Edit Custom Search";
	$content['LN_SEARCH_DELETE'] = "Delete Custom Search";
	$content['LN_SEARCH_ADDEDIT'] = "Add / Edit a Custom Search";
	$content['LN_SEARCH_SELGROUPENABLE'] = ">> Select Group to enable <<";
	$content['LN_SEARCH_ERROR_DISPLAYNAMEEMPTY'] = "The DisplayName cannot be empty.";
	$content['LN_SEARCH_ERROR_SEARCHQUERYEMPTY'] = "The SearchQuery cannot be empty.";
	$content['LN_SEARCH_HASBEENADDED'] = "The Custom Search '%1' has been successfully added.";
	$content['LN_SEARCH_ERROR_IDNOTFOUND'] = "Could not find a search with ID '%1'.";
	$content['LN_SEARCH_ERROR_INVALIDID'] = "Invalid search ID.";
	$content['LN_SEARCH_HASBEENEDIT'] = "The Custom Search '%1' has been successfully edited.";
	$content['LN_SEARCH_WARNDELETESEARCH'] = "Are you sure that you want to delete the Custom Search '%1'? This cannot be undone!";
	$content['LN_SEARCH_ERROR_DELSEARCH'] = "Deleting of the Custom Search with id '%1' failed!";
	$content['LN_SEARCH_ERROR_HASBEENDEL'] = "The Custom Search '%1' has been successfully deleted!";
	$content['LN_SEARCH_'] = "";

// Custom Views center
	$content['LN_VIEWS_CENTER'] = "Views Options";
	$content['LN_VIEWS_ID'] = "ID";
	$content['LN_VIEWS_NAME'] = "View Name";
	$content['LN_VIEWS_COLUMNS'] = "View Columns";
	$content['LN_VIEWS_TYPE'] = "Assigned to";
	$content['LN_VIEWS_ADD'] = "Add new View";
	$content['LN_VIEWS_EDIT'] = "Edit View";
	$content['LN_VIEWS_ERROR_IDNOTFOUND'] = "A View with ID '%1' could not be found.";
	$content['LN_VIEWS_ERROR_INVALIDID'] = "The View with ID '%1' is not a valid View.";
	$content['LN_VIEWS_WARNDELETEVIEW'] = "Are you sure that you want to delete the View '%1'? This cannot be undone!";
	$content['LN_VIEWS_ERROR_DELSEARCH'] = "Deleting of the View with id '%1' failed!";
	$content['LN_VIEWS_ERROR_HASBEENDEL'] = "The View '%1' has been successfully deleted!";
	$content['LN_VIEWS_ADDEDIT'] = "Add / Edit a View";
	$content['LN_VIEWS_COLUMNLIST'] = "Configured Columns";
	$content['LN_VIEWS_ADDCOLUMN'] = "Add Column into list";
	$content['LN_VIEWS_ERROR_DISPLAYNAMEEMPTY'] = "The DisplayName cannot be empty.";
	$content['LN_VIEWS_COLUMN'] = "Column";
	$content['LN_VIEWS_COLUMN_REMOVE'] = "Remove Column";
	$content['LN_VIEWS_HASBEENADDED'] = "The Custom View '%1' has been successfully added.";
	$content['LN_VIEWS_ERROR_NOCOLUMNS'] = "You need to add at least one column in order to add a new Custom View.";
	$content['LN_VIEWS_HASBEENEDIT'] = "The Custom View '%1' has been successfully edited.";
	$content['LN_VIEWS_'] = "";

// Custom DBMappings center
	$content['LN_DBMP_CENTER'] = "Database Field Mappings Options";
	$content['LN_DBMP_ID'] = "ID";
	$content['LN_DBMP_NAME'] = "Database Mappingname";
	$content['LN_DBMP_DBMAPPINGS'] = "Database Mappings";
	$content['LN_DBMP_ADD'] = "Add new Database Mapping";
	$content['LN_DBMP_EDIT'] = "Edit Database Mapping";
	$content['LN_DBMP_DELETE'] = "Delete Database Mapping";
	$content['LN_DBMP_ERROR_IDNOTFOUND'] = "A Database Mapping with ID '%1' could not be found.";
	$content['LN_DBMP_ERROR_INVALIDID'] = "The Database Mapping with ID '%1' is not a valid Database Mapping.";
	$content['LN_DBMP_WARNDELETEMAPPING'] = "Are you sure that you want to delete the Database Mapping '%1'? This cannot be undone!";
	$content['LN_DBMP_ERROR_DELSEARCH'] = "Deleting of the Database Mapping with id '%1' failed!";
	$content['LN_DBMP_ERROR_HASBEENDEL'] = "The Database Mapping '%1' has been successfully deleted!";
	$content['LN_DBMP_ADDEDIT'] = "Add / Edit Database Mapping";
	$content['LN_DBMP_DBMAPPINGSLIST'] = "Configured Mappings";
	$content['LN_DBMP_ADDMAPPING'] = "Add Field Mapping into list";
	$content['LN_DBMP_ERROR_DISPLAYNAMEEMPTY'] = "The DisplayName cannot be empty.";
	$content['LN_DBMP_MAPPING'] = "Mapping";
	$content['LN_DBMP_MAPPING_REMOVE'] = "Remove Mapping";
	$content['LN_DBMP_MAPPING_EDIT'] = "Edit Mapping";
	$content['LN_DBMP_HASBEENADDED'] = "The Custom Database Mapping '%1' has been successfully added.";
	$content['LN_DBMP_ERROR_NOCOLUMNS'] = "You need to add at least one column in order to add a new Custom Database Mapping.";
	$content['LN_DBMP_HASBEENEDIT'] = "The Custom Database Mapping '%1' has been successfully edited.";
	$content['LN_DBMP_ERROR_MISSINGFIELDNAME'] = "Missing mapping for the '%1' field.";
	$content['LN_SOURCES_FILTERSTRING'] = "Custom Searchfilter";
	$content['LN_SOURCES_FILTERSTRING_HELP'] = "Use the same syntax as in the search field. For example if you want to show only messages from 'server1', use this searchfilter: source:=server1";

// Custom Sources center
	$content['LN_SOURCES_CENTER'] = "Sources Options";
	$content['LN_SOURCES_EDIT'] = "Edit Source";
	$content['LN_SOURCES_DELETE'] = "Delete Source";
	$content['LN_SOURCES_ID'] = "ID";
	$content['LN_SOURCES_NAME'] = "Source Name";
	$content['LN_SOURCES_TYPE'] = "Source Type";
	$content['LN_SOURCES_ASSIGNTO'] = "Assigned To";
	$content['LN_SOURCES_DISK'] = "Diskfile";
	$content['LN_SOURCES_DB'] = "MySQL Database";
	$content['LN_SOURCES_CLICKHOUSE'] = "ClickHouse Database";
	$content['LN_SOURCES_PDO'] = "PDO Datasource";
	$content['LN_SOURCES_MONGODB'] = "MongoDB Datasource";
	$content['LN_SOURCES_ADD'] = "Add new Source";
	$content['LN_SOURCES_ADDEDIT'] = "Add / Edit a Source";
	$content['LN_SOURCES_TYPE'] = "Source Type";
	$content['LN_SOURCES_DISKTYPEOPTIONS'] = "Diskfile related Options";
	$content['LN_SOURCES_ERROR_MISSINGPARAM'] = "The paramater '%1' is missing.";
	$content['LN_SOURCES_ERROR_NOTAVALIDFILE'] = "Failed to open the syslog file '%1'! Check if the file exists and LogAnalyzer has sufficient rights to it";
	$content['LN_SOURCES_ERROR_UNKNOWNSOURCE'] = "Unknown Source '%1' detected";
	$content['LN_SOURCE_HASBEENADDED'] = "The new Source '%1' has been successfully added.";
	$content['LN_SOURCES_EDIT'] = "Edit Source";
	$content['LN_SOURCES_ERROR_INVALIDORNOTFOUNDID'] = "The Source-ID is invalid or could not be found.";
	$content['LN_SOURCES_ERROR_IDNOTFOUND'] = "The Source-ID could not be found in the database.";
	$content['LN_SOURCES_HASBEENEDIT'] = "The Source '%1' has been successfully edited.";
	$content['LN_SOURCES_WARNDELETESEARCH'] = "Are you sure that you want to delete the Source '%1'? This cannot be undone!";
	$content['LN_SOURCES_ERROR_DELSOURCE'] = "Deleting of the Source with id '%1' failed!";
	$content['LN_SOURCES_ERROR_HASBEENDEL'] = "The Source '%1' has been successfully deleted!";
	$content['LN_SOURCES_DESCRIPTION'] = "Source Description (Optional)";
	$content['LN_SOURCES_ERROR_INVALIDVALUE'] = "Invalid value for the paramater '%1'.";
	$content['LN_SOURCES_STATSNAME'] = "Name";
	$content['LN_SOURCES_STATSVALUE'] = "Value";
	$content['LN_SOURCES_DETAILS'] = "Details for this logstream source";
	$content['LN_SOURCES_STATSDETAILS'] = "Statistic details for this logstream source";
	$content['LN_SOURCES_ERROR_NOSTATSDATA'] = "Could not find or obtain any stats related information for this logstream source.";
	$content['LN_SOURCES_ERROR_NOCLEARSUPPORT'] = "This logstream source does not support deleting data.";
	$content['LN_SOURCES_ROWCOUNT'] = "Total Rowcount";
	$content['LN_SOURCES_CLEARDATA'] = "The following database maintenance Options are available";
	$content['LN_SOURCES_CLEAROPTIONS'] = "Select how you want to clear data.";
	$content['LN_SOURCES_CLEARALL'] = "Clear (Delete) all data.";
	$content['LN_SOURCES_CLEAR_HELPTEXT'] = "Attention! Be carefull with deleting data, any action performed here can not be undone!";
	$content['LN_SOURCES_CLEARSINCE'] = "Clear all data older than ... ";
	$content['LN_SOURCES_CLEARDATE'] = "Clear all data which is older than ... ";
	$content['LN_SOURCES_CLEARDATA_SEND'] = "Clear selected data range";
	$content['LN_SOURCES_ERROR_INVALIDCLEANUP'] = "Invalid Data Cleanup type";
	$content['LN_SOURCES_WARNDELETEDATA'] = "Are you sure that you want to clear logdata in the '%1' source? This cannot be undone!";
	$content['LN_SOURCES_ERROR_DELDATA'] = "Could not delete data in the '%1' source";
	$content['LN_SOURCES_HASBEENDELDATA'] = "Successfully deleted data from the '%1' source, '%2' rows were affected. ";

// Database Upgrade
	$content['LN_DBUPGRADE_TITLE'] = "LogAnalyzer Database Update";
	$content['LN_DBUPGRADE_DBFILENOTFOUND'] = "The database upgrade file '%1' could not be found in the include folder! Please check if all files were successfully uploaded.";
	$content['LN_DBUPGRADE_DBDEFFILESHORT'] = "The database upgrade files where empty or did not contain any SQL Command! Please check if all files were successfully uploaded.";
	$content['LN_DBUPGRADE_WELCOME'] = "Welcome to the database upgrade";
	$content['LN_DBUPGRADE_BEFORESTART'] = "Before you start upgrading your database, you should create a <b>FULL BACKUP OF YOUR DATABASE</b>. Anything else will be done automatically by the upgrade Script.";
	$content['LN_DBUPGRADE_CURRENTINSTALLED'] = "Current Installed Database Version";
	$content['LN_DBUPGRADE_TOBEINSTALLED'] = "Do be Installed Database Version";
	$content['LN_DBUPGRADE_HASBEENDONE'] = "Database Update has been performed, see the results below";
	$content['LN_DBUPGRADE_SUCCESSEXEC'] = "Successfully executed statements";
	$content['LN_DBUPGRADE_FAILEDEXEC'] = "Failed statements";
	$content['LN_DBUPGRADE_ONESTATEMENTFAILED'] = "At least one statement failed, you may need to correct and fix this issue manually. See error details below";
	$content['LN_DBUPGRADE_ERRMSG'] = "Error Message";
	$content['LN_DBUPGRADE_ULTRASTATSDBVERSION'] = "LogAnalyzer Database Version";

// Charts Options
	$content['LN_CHARTS_CENTER'] = "Charts Options";
	$content['LN_CHARTS_EDIT'] = "Edit Chart";
	$content['LN_CHARTS_DELETE'] = "Delete Chart";
	$content['LN_CHARTS_ADD'] = "Add new Chart";
	$content['LN_CHARTS_ADDEDIT'] = "Add / Edit a Chart";
	$content['LN_CHARTS_NAME'] = "Chart Name";
	$content['LN_CHARTS_ENABLED'] = "Chart enabled";
	$content['LN_CHARTS_ENABLEDONLY'] = "Enabled";
	$content['LN_CHARTS_ERROR_INVALIDORNOTFOUNDID'] = "The Chart-ID is invalid or could not be found.";
	$content['LN_CHARTS_WARNDELETESEARCH'] = "Are you sure that you want to delete the Chart '%1'? This cannot be undone!";
	$content['LN_CHARTS_ERROR_DELCHART'] = "Deleting of the Chart with id '%1' failed!";
	$content['LN_CHARTS_ERROR_HASBEENDEL'] = "The Chart '%1' has been successfully deleted!";
	$content['LN_CHARTS_ERROR_MISSINGPARAM'] = "The paramater '%1' is missing.";
	$content['LN_CHARTS_HASBEENADDED'] = "The new Chart '%1' has been successfully added.";
	$content['LN_CHARTS_ERROR_IDNOTFOUND'] = "The Chart-ID could not be found in the database.";
	$content['LN_CHARTS_HASBEENEDIT'] = "The Chart '%1' has been successfully edited.";
	$content['LN_CHARTS_ID'] = "ID";
	$content['LN_CHARTS_ASSIGNTO'] = "Assigned To";
	$content['LN_CHARTS_PREVIEW'] = "Preview Chart in a new Window";
	$content['LN_CHARTS_FILTERSTRING'] = "Custom Filter";
	$content['LN_CHARTS_FILTERSTRING_HELP'] = "Use the same syntax as in the search field. For example if you want to generate a chart for 'server1', use this filter: source:=server1";
	$content['LN_CHARTS_ERROR_CHARTIDNOTFOUND'] = "Error, ChartID with ID '%1' , was not found";
	$content['LN_CHARTS_ERROR_SETTINGFLAG'] = "Error setting flag, invalid ChartID or operation.";
	$content['LN_CHARTS_SETENABLEDSTATE'] = "Toggle Enabled State";

// Fields Options
	$content['LN_FIELDS_CENTER'] = "Fields Options";
	$content['LN_FIELDS_EDIT'] = "Edit Field";
	$content['LN_FIELDS_DELETE'] = "Delete Field";
	$content['LN_FIELDS_ADD'] = "Add new Field";
	$content['LN_FIELDS_ID'] = "FieldID";
	$content['LN_FIELDS_NAME'] = "Display Name";
	$content['LN_FIELDS_DEFINE'] = "Internal FieldID";
	$content['LN_FIELDS_DELETE_FROMDB'] = "Delete Field from DB";
	$content['LN_FIELDS_ADDEDIT'] = "Add / Edit a Field";
	$content['LN_FIELDS_TYPE'] = "Field Type";
	$content['LN_FIELDS_ALIGN'] = "Listview Alignment";
	$content['LN_FIELDS_SEARCHONLINE'] = "Enable online search";
	$content['LN_FIELDS_DEFAULTWIDTH'] = "Row width in Listview";
	$content['LN_FIELDS_ERROR_IDNOTFOUND'] = "The Field-ID could not be found in the database, or in the default constants.";
	$content['LN_FIELDS_ERROR_INVALIDID'] = "The Field with ID '%1' is not a valid Field.";
	$content['LN_FIELDS_SEARCHFIELD'] = "Name of Searchfilter";
	$content['LN_FIELDS_WARNDELETESEARCH'] = "Are you sure that you want to delete the Field '%1'? This cannot be undone!";
	$content['LN_FIELDS_ERROR_DELSEARCH'] = "The Field-ID could not be found in the database.";
	$content['LN_FIELDS_ERROR_HASBEENDEL'] = "The Field '%1' has been successfully deleted!";
	$content['LN_FIELDS_ERROR_FIELDCAPTIONEMPTY'] = "The field caption was empty. ";
	$content['LN_FIELDS_ERROR_FIELDIDEMPTY'] = "The field id was empty. ";
	$content['LN_FIELDS_ERROR_SEARCHFIELDEMPTY'] = "The searchfilter was empty. ";
	$content['LN_FIELDS_ERROR_FIELDDEFINEEMPTY'] = "The internal FieldID was empty. ";
	$content['LN_FIELDS_HASBEENEDIT'] = "The configuration for the field '%1' has been successfully edited.";
	$content['LN_FIELDS_HASBEENADDED'] = "The configuration for the field '%1' has been successfully added.";
	$content['LN_FIELDS_'] = "";
	$content['LN_ALIGN_CENTER'] = "center";
	$content['LN_ALIGN_LEFT'] = "left";
	$content['LN_ALIGN_RIGHT'] = "right";
	$content['LN_FILTER_TYPE_STRING'] = "String";
	$content['LN_FILTER_TYPE_NUMBER'] = "Number";
	$content['LN_FILTER_TYPE_DATE'] = "Date";

// Parser Options
	$content['LN_PARSERS_EDIT'] = "Edit Message Parser";
	$content['LN_PARSERS_DELETE'] = "Delete Message Parser";
	$content['LN_PARSERS_ID'] = "Message Parser ID";
	$content['LN_PARSERS_NAME'] = "Message Parser Name";
	$content['LN_PARSERS_DESCRIPTION'] = "Message Parser Description";
	$content['LN_PARSERS_ERROR_NOPARSERS'] = "There were no valid message parsers found in your installation. ";
	$content['LN_PARSERS_HELP'] = "Help";
	$content['LN_PARSERS_HELP_CLICK'] = "Click here for a detailed description";
	$content['LN_PARSERS_INFO'] = "Show more Information for this message parser.";
	$content['LN_PARSERS_INIT'] = "Initialize settings for this message parser.";
	$content['LN_PARSERS_REMOVE'] = "Remove settings for this message parser.";
	$content['LN_PARSERS_ERROR_IDNOTFOUND'] = "There was no message parser with ID '%1' found.";
	$content['LN_PARSERS_ERROR_INVALIDID'] = "Invalid message parser id.";
	$content['LN_PARSERS_DETAILS'] = "Details for this Parser";
	$content['LN_PARSERS_CUSTOMFIELDS'] = "The following Custom fields are needed by this Message Parser.";
	$content['LN_PARSERS_WARNREMOVE'] = "You are about to remove the custom fields needed by the '%1' Message Parser. However you can add these fields again if you change your mind.";
	$content['LN_PARSERS_ERROR_HASBEENREMOVED'] = "All settings ('%2' custom fields) for the Message Parser '%1' have been removed. ";
	$content['LN_PARSERS_ERROR_HASBEENADDED'] = "All required settings ('%2' custom fields) for the Message Parser '%1' have been added. ";
	$content['LN_PARSERS_ERROR_NOFIELDS'] = "The Message Parser '%1' does not have any custom fields to add.";
	$content['LN_PARSERSMENU_LIST'] = "List installed Message Parsers";
	$content['LN_PARSERS_ONLINELIST'] = "All Available Parsers";
	$content['LN_PARSERS_'] = "";

// Command Line stuff
	$content['LN_CMD_NOOP'] = "Operation parameter is missing";
	$content['LN_CMD_NOLOGSTREAM'] = "The logstream source parameter is missing";
	$content['LN_CMD_LOGSTREAMNOTFOUND'] = "Logstream Source with ID '%1' could not be found in the Database!";
	$content['LN_CMD_COULDNOTGETROWCOUNT'] = "Could not obtain rowcount from logstream source '%1'";
	$content['LN_CMD_SUBPARAM1MISSING'] = "Subparameter 1 is missing, it should be set to 'all', 'since' or 'date'. For more details see the documentation.";
	$content['LN_CMD_WRONGSUBOPORMISSING'] = "Either the sub-operation is wrong, or another parameter is missing";
	$content['LN_CMD_FAILEDTOCLEANDATA'] = "Failed to cleandata for the logstream '%1'.";
	$content['LN_CMD_CLEANINGDATAFOR'] = "Cleaning data for logstream source '%1'.";
	$content['LN_CMD_ROWSFOUND'] = "Successfully connected and found '%1' rows in the logstream source.";
	$content['LN_CMD_DELETINGOLDERTHEN'] = "Performing deletion of data entries older then '%1'.";
	$content['LN_CMD_DELETEDROWS'] = "Successfully Deleted '%1' rows in the logstream source.'";
	$content['LN_CMD_'] = "";

// Report Options
	$content['LN_REPORTS_EDIT'] = "Edit Report";
	$content['LN_REPORTS_DELETE'] = "Remove Report";
	$content['LN_REPORTS_REQUIREDFIELDS'] = "Required Fields";
	$content['LN_REPORTS_ERROR_NOREPORTS'] = "There were no valid reports found in your installation.";
	$content['LN_REPORTS_INIT'] = "Initialize settings";
	$content['LN_REPORTS_REMOVE'] = "Remove settings";
	$content['LN_REPORTS_ERROR_IDNOTFOUND'] = "There was no report with ID '%1' found.";
	$content['LN_REPORTS_ERROR_INVALIDID'] = "Invalid report id.";
	$content['LN_REPORTS_DETAILS'] = "Details for this report";
	$content['LN_REPORTS_WARNREMOVE'] = "You are about to remove the custom settings needed by the '%1' report. However you can add these settings again if you change your mind.";
	$content['LN_REPORTS_ERROR_HASBEENREMOVED'] = "All settings for the report '%1' have been removed.";
	$content['LN_REPORTS_ERROR_HASBEENADDED'] = "All required settings for the report '%1' have been added.";
	$content['LN_REPORTS_ERROR_NOFIELDS'] = "The report '%1' does not have any custom settings which can be added.";
	$content['LN_REPORTS_ERROR_REPORTDOESNTNEEDTOBEREMOVED'] = "The report '%1' does not need to be removed or initialized.";
	$content['LN_REPORTS_REMOVESAVEDREPORT'] = "Remove Savedreport";
	$content['LN_REPORTS_CUSTOMTITLE'] = "Report Title";
	$content['LN_REPORTS_CUSTOMCOMMENT'] = "Comment / Description";
	$content['LN_REPORTS_FILTERSTRING'] = "Filterstring";
	$content['LN_REPORTS_OUTPUTFORMAT'] = "Outputformat";
	$content['LN_REPORTS_OUTPUTTARGET'] = "Outputtarget";
	$content['LN_REPORTS_HASBEENADDED'] = "The Savedreport '%1' has been successfully added.";
	$content['LN_REPORTS_HASBEENEDIT'] = "The Savedreport '%1' has been successfully edited.";
	$content['LN_REPORTS_SOURCEID'] = "Logstream source";
	$content['LN_REPORTS_ERROR_SAVEDREPORTIDNOTFOUND'] = "There was no savedreport with ID '%1' found.";
	$content['LN_REPORTS_ERROR_INVALIDSAVEDREPORTID'] = "Invalid savedreport id.";
	$content['LN_REPORTS_WARNDELETESAVEDREPORT'] = "Are you sure that you want to delete the savedreport '%1'?";
	$content['LN_REPORTS_ERROR_DELSAVEDREPORT'] = "Deleting of the savedreport with id '%1' failed!";
	$content['LN_REPORTS_ERROR_HASBEENDEL'] = "The savedreport '%1' has been successfully deleted!";
	$content['LN_REPORTS_FILTERLIST'] = "Filterlist";
	$content['LN_REPORTS_FILTER'] = "Filter";
	$content['LN_REPORTS_ADDFILTER'] = "Add filter";
	$content['LN_REPORTS_FILTER_EDIT'] = "Edit filter";
	$content['LN_REPORTS_FILTER_MOVEUP'] = "Move filter up";
	$content['LN_REPORTS_FILTER_MOVEDOWN'] = "Move filter down";
	$content['LN_REPORTS_FILTER_REMOVE'] = "Remove filter";
	$content['LN_REPORTS_FILTEREDITOR'] = "Filtereditor";
	$content['LN_REPORTS_FILTERSTRING_ONLYEDITIF'] = "Only edit raw filterstring if you know what you are doing! Note if you change the filterstring, any changes made in the Filtereditor will be lost!";
	$content['LN_REPORTS_ADVANCEDFILTERS'] = "Advanced filters";
	$content['LN_REPORTS_ADVANCEDFILTERLIST'] = "List of advanced report filters";
	$content['LN_REPORTS_OUTPUTTARGET_DETAILS'] = "Outputtarget Options";
	$content['LN_REPORTS_OUTPUTTARGET_FILE'] = "Output Path and Filename";
	$content['LN_REPORTS_CRONCMD'] = "Local Report command";
	$content['LN_REPORTS_LINKS'] = "Related Links";
	$content['LN_REPORTS_INSTALLED'] = "Installed";
	$content['LN_REPORTS_NOTINSTALLED'] = "Not installed";
	$content['LN_REPORTS_DOWNLOAD'] = "Download Link";
	$content['LN_REPORTS_SAMPLELINK'] = "Report Sample";
	$content['LN_REPORTS_DETAILSFOR'] = "Details for '%1' report";
	$content['LN_REPORTS_PERFORMANCE_WARNING'] = "Logstream Performance Warning";
	$content['LN_REPORTS_OPTIMIZE_LOGSTREAMSOURCE'] = "Yes, optimize logstream source!";
	$content['LN_REPORTS_OPTIMIZE_INDEXES'] = "The datasource '%1' is not optimized for this report. There is at least one INDEX missing. Creating INDEXES will speedup the report generation. <br><br>Do you want LogAnalyzer to create the necessary INDEXES now? This may take more then a few minutes, so please be patient!";
	$content['LN_REPORTS_ERROR_FAILED_CREATE_INDEXES'] = "Failed to create INDEXES for datasource '%1' with error code '%2'";
	$content['LN_REPORTS_INDEX_CREATED'] = "Logstream INDEXES created";
	$content['LN_REPORTS_INDEX_CREATED_SUCCESS'] = "Successfully created all INDEXES for datasource '%1'.";
	$content['LN_REPORTS_OPTIMIZE_TRIGGER'] = "The datasource '%1' does not have a TRIGGER installed to automatically generate the message checksum on INSERT. Creating the TRIGGER will speedup the report generation. <br><br>Do you want LogAnalyzer to create the TRIGGER now? ";
	$content['LN_REPORTS_TRIGGER_CREATED'] = "Logstream TRIGGER created";
	$content['LN_REPORTS_TRIGGER_CREATED_SUCCESS'] = "Successfully created TRIGGER for datasource '%1'.";
	$content['LN_REPORTS_ERROR_FAILED_CREATE_TRIGGER'] = "Failed to create TRIGGER for datasource '%1' with error code '%2'";
	$content['LN_REPORTS_CHANGE_CHECKSUM'] = "The Checksum field for datasource '%1' is not set to UNSIGNED INT. To get the report work properly, changing the CHECKSUM field to UNSIGNED INT is necessary! <br><br>Do you want LogAnalyzer to change the CHECKSUM field now? This may take more then a few minutes, so please be patient!";
	$content['LN_REPORTS_ERROR_FAILED_CHANGE_CHECKSUM'] = "Failed to change the CHECKSUM field for datasource '%1' with error code '%2'";
	$content['LN_REPORTS_CHECKSUM_CHANGED'] = "Checksum field changed";
	$content['LN_REPORTS_CHECKSUM_CHANGED_SUCCESS'] = "Successfully changed the Checksum field for datasource '%1'.";
	$content['LN_REPORTS_LOGSTREAM_WARNING'] = "Logstream Warning";
	$content['LN_REPORTS_ADD_MISSINGFIELDS'] = "The datasource '%1' does not contain all necessary datafields There is at least one FIELD missing. <br><br>Do you want LogAnalyzer to create the missing datafields now?";
	$content['LN_REPORTS_ERROR_FAILED_ADDING_FIELDS'] = "Failed adding missing datafields in datasource '%1' with error code '%2'";
	$content['LN_REPORTS_FIELDS_CREATED'] = "Added missing datafields";
	$content['LN_REPORTS_FIELDS_CREATED_SUCCESS'] = "Successfully added missing datafields for datasource '%1'.";
	$content['LN_REPORTS_RECHECKLOGSTREAMSOURCE'] = "Do you want to check the current logstream source again?";
	$content['LN_REPORTS_ADDSAVEDREPORT'] = "Add Savedreport and save changes";
	$content['LN_REPORTS_EDITSAVEDREPORT'] = "Save changes";
	$content['LN_REPORTS_ADDSAVEDREPORTANDRETURN'] = "Add Savedreport and return to reportlist";
	$content['LN_REPORTS_EDITSAVEDREPORTANDRETURN'] = "Save changes and return to reportlist";
	$content['LN_REPORTS_SOURCE_WARNING'] = "Logstream Source Warning";	
	$content['LN_REPORTS_ERROR_FAILED_SOURCE_CHECK'] = "Failed to check the datasource '%1' with error '%2'";
	$content['LN_REPORTS_'] = "";


?>
