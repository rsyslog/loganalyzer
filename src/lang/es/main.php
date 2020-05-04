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
$content['LN_MAINTITLE'] = "Principal LogAnalyzer";
$content['LN_MAIN_SELECTSTYLE'] = "Seleccionar Diseño";
$content['LN_GEN_LANGUAGE'] = "Seleccionar Idioma";
$content['LN_GEN_SELECTSOURCE'] = "Seleccionar Fuente de Datos";
$content['LN_GEN_MOREPAGES'] = "Más de una página disponible";
$content['LN_GEN_FIRSTPAGE'] = "Primera Página";
$content['LN_GEN_LASTPAGE'] = "Última Página";
$content['LN_GEN_NEXTPAGE'] = "Siguiente Página";
$content['LN_GEN_PREVIOUSPAGE'] = "Pagina Anterior";
$content['LN_GEN_RECORDCOUNT'] = "Total de registros";
$content['LN_GEN_PAGERSIZE'] = "Registros por página";
$content['LN_GEN_PAGE'] = "Página";
$content['LN_GEN_PREDEFINEDSEARCHES'] = "Búsquedas Predefinidas";
$content['LN_GEN_SOURCE_DISK'] = "Diskfile";
$content['LN_GEN_SOURCE_DB'] = "MYSQL Native";
$content['LN_GEN_SOURCE_CLICKHOUSE'] = "ClickHouse DB";
$content['LN_GEN_SOURCE_PDO'] = "Database (PDO)";
$content['LN_GEN_SOURCE_MONGODB'] = "MongoDB Native";
$content['LN_GEN_RECORDSPERPAGE'] = "registros por página";
$content['LN_GEN_PRECONFIGURED'] = "Preconfigurado";
$content['LN_GEN_AVAILABLESEARCHES'] = "Búsquedas disponibles";
$content['LN_GEN_DB_MYSQL'] = "Mysql Server";
$content['LN_GEN_DB_MSSQL'] = "Microsoft SQL Server";
$content['LN_GEN_DB_ODBC'] = "ODBC Database Source";
$content['LN_GEN_DB_PGSQL'] = "PostgreSQL";
$content['LN_GEN_DB_OCI'] = "Oracle Call Interface";
$content['LN_GEN_DB_DB2'] = "IBM DB2";
$content['LN_GEN_DB_FIREBIRD'] = "Firebird/Interbase 6";
$content['LN_GEN_DB_INFORMIX'] = "IBM Informix Dynamic Server";
$content['LN_GEN_DB_SQLITE'] = "SQLite 2";
$content['LN_GEN_SELECTVIEW'] = "Seleccionar Vista";
$content['LN_GEN_CRITERROR_UNKNOWNTYPE'] = "LogAnalyzer aún no admite el tipo de fuente '%1'. Este es un error crítico, por favor arreglar su configuración.";
$content['LN_GEN_ERRORRETURNPREV'] = "Haga clic aquí para regresar a la página anterior.";
$content['LN_GEN_ERRORDETAILS'] = "Detalles del error:";
$content['LN_SOURCES_ERROR_WITHINSOURCE'] = "La comprobación de origen '%1' retorno con un error:<br>%2";
$content['LN_SOURCES_ERROR_EXTRAMSG'] = "Detalles del error adicionales:<br>%1";
$content['LN_ERROR_NORECORDS'] = "No se encontraron registros de syslog";
$content['LN_ERROR_FILE_NOT_FOUND'] = "No se pudo encontrar el archivo Syslog";
$content['LN_ERROR_FILE_NOT_READABLE'] = "El archivo Syslog no se puede leer, puede ser que no tenga acceso de lectura";
$content['LN_ERROR_UNKNOWN'] = "Se produjo un error desconocido o no controlado (Código de error '%1')";
$content['LN_ERROR_FILE_EOF'] = "Fin del archivo alcanzado";
$content['LN_ERROR_FILE_BOF'] = "Inicio del archivo alcanzado";
$content['LN_ERROR_FILE_CANT_CLOSE'] = "No se puede cerrar el archivo";
$content['LN_ERROR_UNDEFINED'] = "Error no definido";
$content['LN_ERROR_EOS'] = "Fin del flujo alcanzado";
$content['LN_ERROR_FILTER_NOT_MATCH'] = "El filtro no coincide con ningún resultado";
$content['LN_ERROR_DB_CONNECTFAILED'] = "La conexión al servidor de la base de datos falló";
$content['LN_ERROR_DB_CANNOTSELECTDB'] = "No se pudo encontrar la base de datos configurada";
$content['LN_ERROR_DB_QUERYFAILED'] = "La consulta de datos no se pudo ejecutar";
$content['LN_ERROR_DB_NOPROPERTIES'] = "No se encontraron propiedades de la base de datos";
$content['LN_ERROR_DB_INVALIDDBMAPPING'] = "Asignaciones de campo de datos no válidas";
$content['LN_ERROR_DB_INVALIDDBDRIVER'] = "Controlador de base de datos seleccionado no válido";
$content['LN_ERROR_DB_TABLENOTFOUND'] = "No se pudo encontrar la tabla configurada, quizás esté mal escrita o los nombres de las tablas distinguen entre mayúsculas y minúsculas";
$content['LN_ERROR_DB_DBFIELDNOTFOUND'] = "No se pudo encontrar la asignación de campo de base de datos para al menos un campo.";
$content['LN_GEN_SELECTEXPORT'] = "&gt; Seleccionar Formato de Exportación &lt;";
$content['LN_GEN_EXPORT_CVS'] = "CSV (Separado por comas)";
$content['LN_GEN_EXPORT_XML'] = "XML";
$content['LN_GEN_EXPORT_PDF'] = "PDF";
$content['LN_GEN_ERROR_EXPORING'] = "Error al exportar datos";
$content['LN_GEN_ERROR_INVALIDEXPORTTYPE'] = "Se seleccionó un formato de exportación no válido u otros parámetros fueron incorrectos.";
$content['LN_GEN_ERROR_SOURCENOTFOUND'] = "No se pudo encontrar la fuente con ID '%1'.";
$content['LN_GEN_MOREINFORMATION'] = "Más Información";
$content['LN_FOOTER_PAGERENDERED'] = "Página generada en";
$content['LN_FOOTER_DBQUERIES'] = "Consultas DB";
$content['LN_FOOTER_GZIPENABLED'] = "GZIP habilitado";
	$content['LN_FOOTER_SCRIPTTIMEOUT'] = "Script Timeout";
$content['LN_FOOTER_SECONDS'] = "segundos";
$content['LN_WARNING_LOGSTREAMTITLE'] = "Advertencia del flujo de registros";
$content['LN_WARNING_LOGSTREAMDISK_TIMEOUT'] = "Mientras leía el flujo de registros, el tiempo de espera del script php me obligó a abortar en este punto.<br><br>Si desea evitar esto, aumente el tiempo de espera del script LogAnalyzer en su config.php. Si el sistema de usuario está instalado, puede hacerlo en el Centro de administración.";
$content['LN_ERROR_FILE_NOMORETIME'] = "No queda más tiempo para procesar";
$content['LN_WARNING_DBUPGRADE'] = "Actualización de base de datos requerida";
$content['LN_WARNING_DBUPGRADE_TEXT'] = "La versión actual de la base de datos instalada es '%1'.<br>Hay disponible una actualización a la versión '%2'.";
$content['LN_ERROR_REDIRECTABORTED'] = 'La redirección automática a la <a href="%1">página</a> se canceló, ya que se produjo un error interno. Consulte los detalles de error anteriores y póngase en contacto con nuestros foros de soporte si necesita ayuda.';
$content['LN_DEBUGLEVEL'] = "Nivel de Depuración";
$content['LN_DEBUGMESSAGE'] = "Mensaje de Depuración";
$content['LN_GEN_REPORT_OUTPUT_HTML'] = "Formato HTML";
$content['LN_GEN_REPORT_OUTPUT_PDF'] = "Formato PDF";
$content['LN_GEN_REPORT_TARGET_STDOUT'] = "Salida Directa";
$content['LN_GEN_REPORT_TARGET_FILE'] = "Guardar en Archivo";
$content['LN_GEN_REPORT_TARGET_EMAIL'] = "Enviar como Correo Electrónico";
$content['LN_GEN_UNKNOWN'] = "Desconocido";
$content['LN_GEN_AUTH_INTERNAL'] = "Autenticación interna";
$content['LN_GEN_AUTH_LDAP'] = "Autenticación LDAP";

// Topmenu Entries
$content['LN_MENU_SEARCH'] = "Buscar";
$content['LN_MENU_SHOWEVENTS'] = "Mostrar Eventos";
$content['LN_MENU_HELP'] = "Ayuda";
$content['LN_MENU_DOC'] = "Documentación";
$content['LN_MENU_FORUM'] = "Foro de Soporte";
	$content['LN_MENU_WIKI'] = "LogAnalyzer Wiki";
$content['LN_MENU_PROSERVICES'] = "Servicios Profesionales";
	//$content['LN_MENU_SEARCHINKB'] = "Search in Knowledge Base";
	$content['LN_MENU_SEARCHINKB'] = "Buscar en la Base de Conocimiento";
$content['LN_MENU_LOGIN'] = "Iniciar Sesión";
$content['LN_MENU_ADMINCENTER'] = "Centro de Administración";
$content['LN_MENU_LOGOFF'] = "Desconectarse";
$content['LN_MENU_LOGGEDINAS'] = "Conectado como";
$content['LN_MENU_MAXVIEW'] = "Vista Maxima";
$content['LN_MENU_NORMALVIEW'] = "Vista Normalizar";
$content['LN_MENU_STATISTICS'] = "Estadísticas";
$content['LN_MENU_CLICKTOEXPANDMENU'] = "Haga clic en el icono para mostrar el menú.";
$content['LN_MENU_REPORTS'] = "Informes";

// Main Index Site
$content['LN_ERROR_INSTALLFILEREMINDER'] = "¡Advertencia! ¡Todavía NO ha eliminado el 'install.php' de su directorio principal de LogAnalyzer!";
$content['LN_TOP_NUM'] = "No.";
$content['LN_TOP_UID'] = "uID";
$content['LN_GRID_POPUPDETAILS'] = "Detalles del mensaje de Syslog con ID '%1'";
$content['LN_SEARCH_USETHISBLA'] = "Use el formulario a continuación y su búsqueda avanzada aparecerá aquí";
$content['LN_SEARCH_FILTER'] = "Buscar (filtro):";
$content['LN_SEARCH_ADVANCED'] = "Búsqueda Avanzada";
$content['LN_SEARCH'] = "Buscar";
$content['LN_SEARCH_RESET'] = "Restablecer Búsqueda";
$content['LN_SEARCH_PERFORMADVANCED'] = "Realizar Búsqueda Avanzada";
$content['LN_VIEW_MESSAGECENTERED'] = "Volver a la vista sin filtrar con este mensaje en la parte superior";
$content['LN_VIEW_RELATEDMSG'] = "Ver mensajes relacionados de syslog";
$content['LN_VIEW_FILTERFOR'] = "Filtrar mensaje para ";
$content['LN_VIEW_SEARCHFOR'] = "Busque en línea para ";
$content['LN_VIEW_SEARCHFORGOOGLE'] = "Busca en Google para ";
$content['LN_GEN_MESSAGEDETAILS'] = "Detalles del mensaje";
$content['LN_VIEW_ADDTOFILTER'] = "Agregue '%1' al conjunto de filtros";
$content['LN_VIEW_EXCLUDEFILTER'] = "Excluir '%1' del conjunto de filtros";
$content['LN_VIEW_FILTERFORONLY'] = "Filtrar solo por '%1'";
$content['LN_VIEW_SHOWALLBUT'] = "Mostrar todo excepto '%1'";
$content['LN_VIEW_VISITLINK'] = "Abra el enlace '%1' en una nueva ventana";

$content['LN_HIGHLIGHT'] = "Destacar >>";
$content['LN_HIGHLIGHT_OFF'] = "Destacar <<";
$content['LN_HIGHLIGHT_WORDS'] = "Destacar palabras separadas por comas";

$content['LN_AUTORELOAD'] = "Establecer recarga automática";
$content['LN_AUTORELOAD_DISABLED'] = "Recarga automática deshabilitada";
$content['LN_AUTORELOAD_PRECONFIGURED'] = "Recarga automática preconfigurada ";
$content['LN_AUTORELOAD_SECONDS'] = "segundos";
$content['LN_AUTORELOAD_MINUTES'] = "minutos";

// Filter Options
$content['LN_FILTER_DATE'] = "Rango de Fecha y Hora";
$content['LN_FILTER_DATEMODE'] = "Seleccionar modo";
$content['LN_DATEMODE_ALL'] = "Todo el tiempo";
$content['LN_DATEMODE_RANGE'] = "Intervalo de tiempo";
$content['LN_DATEMODE_LASTX'] = "Hora x desde hoy";
$content['LN_FILTER_DATEFROM'] = "Rango de fechas desde";
$content['LN_FILTER_DATETO'] = "Rango de fechas hasta";
$content['LN_FILTER_TIMEFROM'] = "Rango de tiempo desde";
$content['LN_FILTER_TIMETO'] = "Rango de tiempo hasta";
$content['LN_FILTER_DATELASTX'] = "Tiempo desde";
$content['LN_FILTER_ADD2SEARCH'] = "Agregar a la búsqueda";
$content['LN_DATE_LASTX_HOUR'] = "Ultima hora";
$content['LN_DATE_LASTX_12HOURS'] = "Últimas 12 horas";
$content['LN_DATE_LASTX_24HOURS'] = "Últimas 24 horas";
$content['LN_DATE_LASTX_7DAYS'] = "Los últimos 7 días";
$content['LN_DATE_LASTX_31DAYS'] = "Los últimos 31 días";
	$content['LN_FILTER_FACILITY'] = "Syslog Facility";
	$content['LN_FILTER_SEVERITY'] = "Syslog Severity";
$content['LN_FILTER_OTHERS'] = "Otros Filtros";
$content['LN_FILTER_MESSAGE'] = "Mensaje de Syslog";
$content['LN_FILTER_SYSLOGTAG'] = "Etiqueta Syslog";
$content['LN_FILTER_SOURCE'] = "Origen (Hostname)";
$content['LN_FILTER_MESSAGETYPE'] = "Tipo de Mensaje";

// Install Page
$content['LN_CFG_DBSERVER'] = "Host de Base de Datos";
$content['LN_CFG_DBPORT'] = "Puerto de Base de Datos";
$content['LN_CFG_DBNAME'] = "Nombre de la Base de Datos";
$content['LN_CFG_DBPREF'] = "Prefijo de Tabla";
$content['LN_CFG_DBUSER'] = "Usuario de Base de Datos";
$content['LN_CFG_DBPASSWORD'] = "Contraseña de Base de Datos";
$content['LN_CFG_PARAMMISSING'] = "Faltaban los siguientes parámetros: ";
$content['LN_CFG_SOURCETYPE'] = "Tipo de Fuente";
$content['LN_CFG_DISKTYPEOPTIONS'] = "Opciones de Tipo de Disco";
$content['LN_CFG_LOGLINETYPE'] = "Tipo de Línea de Registro";
$content['LN_CFG_SYSLOGFILE'] = "Archivo Syslog";
$content['LN_CFG_DATABASETYPEOPTIONS'] = "Opciones de Tipo de Base de Datos";
$content['LN_CFG_DBTABLETYPE'] = "Tipo de Tabla";
	$content['LN_CFG_DBSTORAGEENGINE'] = "Motor de Almacenamiento de Bases de Datos";
$content['LN_CFG_DBTABLENAME'] = "Nombre de Tabla en la Base de Datos";
$content['LN_CFG_NAMEOFTHESOURCE'] = "Nombre de la Fuente";
$content['LN_CFG_FIRSTSYSLOGSOURCE'] = "Primera Fuente de Syslog";
$content['LN_CFG_DBROWCOUNTING'] = "Habilitar recuento de filas";
$content['LN_CFG_VIEW'] = "Seleccione Ver";
$content['LN_CFG_DBUSERLOGINREQUIRED'] = "Requerir que el Usuario Inicie Sesión";
$content['LN_CFG_MSGPARSERS'] = "Analizadores de mensajes (separados por comas)";
$content['LN_CFG_NORMALIZEMSG'] = "Normalizar Mensaje dentro de Analizadores";
$content['LN_CFG_SKIPUNPARSEABLE'] = "Omitir mensajes no analizables (¡Solo funciona si los Analizadores de Mensajes están configurados!)";
$content['LN_CFG_DBRECORDSPERQUERY'] = "Recuento de registros para consultas de Bases de Datos";
$content['LN_CFG_LDAPServer'] = "Servidor LDAP Hostname/IP";
$content['LN_CFG_LDAPPort'] = "Puerto LDAP, predeterminado 389 (636 para SSL)";
$content['LN_CFG_LDAPBaseDN'] = "DN base para Búsqueda LDAP";
$content['LN_CFG_LDAPSearchFilter'] = "Filtro de Búsqueda Básica";
$content['LN_CFG_LDAPUidAttribute'] = "Atributo de Nombre de Usuario LDAP";
$content['LN_CFG_LDAPBindDN'] = "Usuario Privilegiado utilizado para consultas LDAP";
$content['LN_CFG_LDAPBindPassword'] = "Contraseña del Usuario Privilegiado";
$content['LN_CFG_LDAPDefaultAdminUser'] = "Nombre de Usuario Administrativo LDAP predeterminado";
$content['LN_CFG_AUTHTYPE'] = "Método de Autentificación";
$content['LN_GEN_AUTH_LDAP_OPTIONS'] = "Opciones de Autenticación LDAP";

// Details page
$content['LN_DETAILS_FORSYSLOGMSG'] = "Detalles del mensajes de syslog con id";
$content['LN_DETAILS_DETAILSFORMSG'] = "Detalles del mensaje con id";
$content['LN_DETAIL_BACKTOLIST'] = "Volver a la lista";
$content['LN_DETAIL_DYNAMIC_FIELDS'] = "Campos dinamicos";


// Login Site
$content['LN_LOGIN_DESCRIPTION'] = "Use este formulario para iniciar sesión en LogAnalyzer. ";
$content['LN_LOGIN_TITLE'] = "Iniciar Sesión";
$content['LN_LOGIN_USERNAME'] = "Usuario";
$content['LN_LOGIN_PASSWORD'] = "Contraseña";
$content['LN_LOGIN_SAVEASCOOKIE'] = "Mantenerme conectado";
$content['LN_LOGIN_ERRWRONGPASSWORD'] = "¡Usuario o contraseña incorrectos!";
$content['LN_LOGIN_USERPASSMISSING'] = "Usuario o contraseña no proporcionados";
$content['LN_LOGIN_LDAP_USERNOTFOUND'] = "No se pudo encontrar el usuario '%1'";
$content['LN_LOGIN_LDAP_USERCOULDNOTLOGIN'] = "No se pudo iniciar sesión con el usuario '%1', error de LDAP:%2";
$content['LN_LOGIN_LDAP_PASSWORDFAIL'] = "El usuario '%1' no pudo iniciar sesión con esa contraseña";
$content['LN_LOGIN_LDAP_SERVERFAILED'] = "Error al conectarse al servidor LDAP '%1'";
$content['LN_LOGIN_LDAP_USERBINDFAILED'] = "No se pudo vincular con el usuario de búsqueda DN '%1'";


// Install Site
$content['LN_INSTALL_TITLETOP'] = "Instalación de LogAnalyzer Versión %1 - Paso %2";
$content['LN_INSTALL_TITLE'] = "Paso del instalador %1";
$content['LN_INSTALL_ERRORINSTALLED'] = '¡LogAnalyzer ya está configurado!<br><br>Si desea reconfigurar LogAnalyzer, elimine <B>config.php</B> actual o reemplácelo con un archivo vacío.<br><br>Haga clic <A HREF="index.php">aquí</A> para volver a la página de inicio de pgpLogCon.';
$content['LN_INSTALL_FILEORDIRNOTWRITEABLE'] = "Al menos un archivo o directorio (o más) no se puede escribir, compruebe los permisos del archivo (chmod 666)!";
$content['LN_INSTALL_SAMPLECONFIGMISSING'] = "Falta el archivo de configuración de muestra '%1'. No ha cargado completamente LogAnalyzer.";
$content['LN_INSTALL_ERRORCONNECTFAILED'] = "¡Conexión con la base de datos '%1' ha fallado! ¡Por favor verifique el Nombre del Servidor, Puerto, Usuario y Contraseña!";
$content['LN_INSTALL_ERRORACCESSDENIED'] = "¡No se puede usar la base de datos '%1'! ¡Si la base de datos no existe, créela o verifique los permisos de acceso de los usuarios!";
$content['LN_INSTALL_ERRORINVALIDDBFILE'] = "¡Error, archivo de definición de base de datos no válido (para abreviar), el nombre del archivo es '%1'! Por favor, compruebe si el archivo se ha subido correctamente.";
$content['LN_INSTALL_ERRORINSQLCOMMANDS'] = "¡Error, archivo de definición de base de datos no válido (no se encontraron sentencias SQL), el nombre del archivo es '%1'!<br>¡Compruebe si el archivo no se cargó correctamente o póngase en contacto con los foros de LogAnalyzer para obtener ayuda!";
$content['LN_INSTALL_MISSINGUSERNAME'] = "El nombre de usuario necesita ser especificado";
$content['LN_INSTALL_PASSWORDNOTMATCH'] = "¡La contraseña no coincide o es corta!";
$content['LN_INSTALL_FAILEDTOOPENSYSLOGFILE'] = "¡Error al abrir el archivo syslog '%1'! Compruebe si el archivo existe y LogAnalyzer tiene suficientes permisos<br>";
$content['LN_INSTALL_FAILEDCREATECFGFILE'] = "¡No se pudo crear el archivo de configuración en '%1'! ¡Por favor verifique los permisos del archivo!";
$content['LN_INSTALL_FAILEDREADINGFILE'] = "¡Error al leer el archivo '%1'! ¡Por favor verifique si el archivo existe!";
	$content['LN_INSTALL_ERRORREADINGDBFILE'] = "¡Error al leer el archivo de definición de base de datos predeterminado en '%1'! ¡Por favor verifique si el archivo existe!";
$content['LN_INSTALL_STEP1'] = "Paso 1 - Requisitos Previos";
$content['LN_INSTALL_STEP2'] = "Paso 2 - Verificar los Permisos de Archivos";
$content['LN_INSTALL_STEP3'] = "Paso 3 - Configuración Básica";
$content['LN_INSTALL_STEP4'] = "Paso 4 - Crear Tablas";
$content['LN_INSTALL_STEP5'] = "Paso 5 - Comprobar los Resultados de SQL";
$content['LN_INSTALL_STEP6'] = "Paso 6 - Creación de la Cuenta de Usuario Principal";
$content['LN_INSTALL_STEP7'] = "Paso 7 - Cree la Primera Fuente para Mensajes de Syslog";
$content['LN_INSTALL_STEP8'] = "Paso 8 - Hecho";
$content['LN_INSTALL_STEP1_TEXT'] = 'Antes de comenzar a instalar LogAnalyzer, la configuración del instalador debe verificar algunas cosas primero.<br>Es posible que primero deba corregir algunos permisos de archivo.<br><br>Haga clic en <input type="submit" value="Siguiente"> para comenzar la prueba.';
$content['LN_INSTALL_STEP2_TEXT'] = "Se han verificado los siguientes permisos de archivo. ¡Verifique los resultados a continuación!<br>Puede usar el script <B>configure.sh</B> de la carpeta <B>contrib</B> para establecer los permisos por usted.";
$content['LN_INSTALL_STEP3_TEXT'] = "En este paso, configurará las configuraciones básicas para LogAnalyzer.";
$content['LN_INSTALL_STEP4_TEXT'] = '¡Si alcanzó este paso, la conexión de la base de datos se ha verificado con éxito!<br><br>
El siguiente paso será crear las tablas de base de datos necesarias utilizadas por el sistema de usuario LogAnalyzer. ¡Esto puede llevar un tiempo!<br>
<b>ADVERTENCIA</b>, si tiene una instalación de LogAnalyzer existente en esta base de datos con el mismo prefijo de tabla, ¡todos sus datos serán <b>SOBRESCRITOS</b>! Asegúrese de estar utilizando una nueva base de datos, o si desea sobrescribir su antigua base de datos LogAnalyzer.<br><br>
<b>Haga clic en <input type="submit" value="Siguiente"> para comenzar la creación de las tablas</b>
';
$content['LN_INSTALL_STEP5_TEXT'] = "Se han creado las tablas. Consulte la lista a continuación para ver posibles errores";
$content['LN_INSTALL_STEP6_TEXT'] = "Ahora está a punto de crear la cuenta de usuario de LogAnalyzer inicial.<br>¡Este será el primer usuario administrativo, que será necesario para iniciar sesión en LogAnalyzer y acceder al Centro de administración!";
$content['LN_INSTALL_STEP8_TEXT'] = '¡Felicidades! ¡Has instalado correctamente LogAnalyzer :)!<br><br>Haga clic <a href="index.php">aquí</a> para ir a su instalación.';
$content['LN_INSTALL_PROGRESS'] = "Progreso de la Instalación: ";
$content['LN_INSTALL_FRONTEND'] = "Opciones Frontend";
$content['LN_INSTALL_NUMOFSYSLOGS'] = "Número de mensajes de syslog por página";
$content['LN_INSTALL_MSGCHARLIMIT'] = "Límite de caracteres del mensaje para la vista principal";
$content['LN_INSTALL_STRCHARLIMIT'] = "Límite de visualización de caracteres para todos los campos de tipo string";
	$content['LN_INSTALL_SHOWDETAILPOP'] = "Show message details popup";
	$content['LN_INSTALL_AUTORESOLVIP'] = "Resolver automáticamente las direcciones IP (inline)";
$content['LN_INSTALL_USERDBOPTIONS'] = "Opciones de Base de Datos de Usuario";
$content['LN_INSTALL_ENABLEUSERDB'] = "Habilitar Base de Datos de Usuario";
$content['LN_INSTALL_SUCCESSSTATEMENTS'] = "Declaraciones ejecutadas con éxito:";
$content['LN_INSTALL_FAILEDSTATEMENTS'] = "Declaraciones fallidas:";
$content['LN_INSTALL_STEP5_TEXT_NEXT'] = "¡Ahora puede continuar con el <B>siguiente</B> paso agregando el primer usuario administrador de LogAnalyzer!";
$content['LN_INSTALL_STEP5_TEXT_FAILED'] = "Al menos una declaración falló, ver razones de error a continuación";
$content['LN_INSTALL_ERRORMSG'] = "Mensaje de Error";
$content['LN_INSTALL_SQLSTATEMENT'] = "Instrucción SQL";
$content['LN_INSTALL_CREATEUSER'] = "Crear Cuenta de Usuario";
$content['LN_INSTALL_PASSWORD'] = "Contraseña";
$content['LN_INSTALL_PASSWORDREPEAT'] = "Repite la contraseña";
$content['LN_INSTALL_SUCCESSCREATED'] = "Usuario creado con éxito";
$content['LN_INSTALL_RECHECK'] = "Volver a Comprobar";
	$content['LN_INSTALL_FINISH'] = "Finish!";
$content['LN_INSTALL_LDAPCONNECTFAILED'] = "No se pudo conectar a su servidor LDAP '%1'.";
$content['LN_INSTALL_WARNINGMYSQL'] = "Se requiere un servidor de base de datos MYSQL para esta función. Otros motores de base de datos no son compatibles con el Sistema de base de datos de usuario. Sin embargo, para las fuentes de registro, hay soporte para otros sistemas de bases de datos.";
$content['LN_INSTALL_'] = "";

// Converter Site
$content['LN_CONVERT_TITLE'] = "Convertidor de configuración Paso %1";
$content['LN_CONVERT_NOTALLOWED'] = "Iniciar Sesión";
$content['LN_CONVERT_ERRORINSTALLED'] = 'LogAnalyzer no tiene permitido convertir su configuración en la base de datos de usuarios.<br><br>Si desea convertir su configuración de conversión, agregue la siguiente variable en su config.php: <br><b>$CFG[\'UserDBConvertAllowed\'] = true;</b><br><br> Haga clic en <A HREF="index.php">aquí</A> para volver a la página de inicio de pgpLogCon.';
$content['LN_CONVERT_STEP1'] = "Paso 1 - Informacion";
$content['LN_CONVERT_STEP2'] = "Paso 2 - Crear Tablas";
$content['LN_CONVERT_STEP3'] = "Paso 3 - Comprobar los resultados de SQL";
$content['LN_CONVERT_STEP4'] = "Paso 4 - Creación de la Cuenta de Usuario Principal";
$content['LN_CONVERT_STEP5'] = "Paso 5 - Importar configuraciones en la base de datos de usuario";
$content['LN_CONVERT_STEP6'] = "Paso 8 - Hecho";
$content['LN_CONVERT_TITLETOP'] = "Conversión de la configuración de LogAnalyzer - Paso ";
$content['LN_CONVERT_STEP1_TEXT'] = 'Este script le permite importar su configuración existente desde el archivo <b>config.php</b>. Esto incluye configuraciones frontend, fuentes de datos, vistas personalizadas y búsquedas personalizadas. Solo realice esta conversión si instaló LogAnalyzer sin el sistema de base de datos de usuario y decidió habilitarlo ahora.<br><br><b>¡CUALQUIER INSTANCIA EXISTENTE DE UN BASE DE DATOS DE USUARIO SE SOBREESCRIBIRÁ!</b><br><br><input type="submit" value="Haga clic aquí"> para comenzar el primer paso de conversión!';
$content['LN_CONVERT_STEP2_TEXT'] = '¡La conexión de la base de datos ha sido verificada con éxito!<br><br>El siguiente paso será crear las tablas de base de datos necesarias para el sistema de usuario LogAnalyzer. ¡Esto podría tomar un tiempo!<br><b>ADVERTENCIA</b>, si tiene una instalación de LogAnalyzer existente en esta base de datos con el mismo prefijo de tabla, ¡todos sus datos serán <b>SOBRESCRITOS</b>!<br> Asegúrese de estar utilizando un nueva base de datos, o si desea sobrescribir su antigua base de datos LogAnalyzer.<br><br><b>Haga clic en <input type="submit" value="Siguiente"> para comenzar la creación de las tablas </b>';
$content['LN_CONVERT_STEP5_TEXT'] = '<input type="submit" value="Haga clic aquí"> para comenzar el último paso de la conversión. En este paso, su configuración existente desde <b>config.php</b> se importará a la base de datos.';
$content['LN_CONVERT_STEP6_TEXT'] = '¡Felicidades! ¡Ha convertido con éxito su instalación existente de LogAnalyzer :)!<br><br>¡Importante! ¡No olvides ELIMINAR LAS VARIABLES <b>$CFG[\'UserDBConvertAllowed\'] = true;</b> de tu archivo <b>config.php</b>!<br><br>Puede hacer clic <a href="index.php">aquí</a> para acceder a su instalación de LogAnalyzer.';
$content['LN_CONVERT_PROCESS'] = "Progreso de conversión:";
$content['LN_CONVERT_ERROR_SOURCEIMPORT'] = "Error crítico al importar las fuentes en la base de datos, el Tipo de fuente '%1' no es compatible con esta versión de LogAnalyzer.";

// Stats Site
$content['LN_STATS_CHARTTITLE'] = "Principales %1 '%2' ordenados por recuento de mensajes";
	$content['LN_STATS_COUNTBY'] = "Recuento de mensajes de '%1'";
	$content['LN_STATS_OTHERS'] = "Todos los demas";
	$content['LN_STATS_TOPRECORDS'] = "Max registros: %1";
	$content['LN_STATS_GENERATEDAT'] = "Generado en: %1";
//	$content['LN_STATS_COUNTBYSYSLOGTAG'] = "Conteo de mensajes por SyslogTag";
$content['LN_STATS_GRAPH'] = "Grafico";
$content['LN_GEN_ERROR_INVALIDFIELD'] = "Nombre de campo no válido";
$content['LN_GEN_ERROR_MISSINGCHARTFIELD'] = "Falta el nombre del campo";
$content['LN_GEN_ERROR_INVALIDTYPE'] = "Tipo de gráfico no válido o desconocido.";
$content['LN_ERROR_CHARTS_NOTCONFIGURED'] = "No hay gráficos configurados.";
$content['LN_CHART_TYPE'] = "Tipo de gráfico";
$content['LN_CHART_WIDTH'] = "Ancho del gráfico";
$content['LN_CHART_FIELD'] = "Campo de gráfico";
$content['LN_CHART_MAXRECORDS'] = "Cantidad de los principales registros";
$content['LN_CHART_SHOWPERCENT'] = "Mostrar datos en porcentaje";
$content['LN_CHART_TYPE_CAKE'] = "Circular";
$content['LN_CHART_TYPE_BARS_VERTICAL'] = "Barras verticales";
$content['LN_CHART_TYPE_BARS_HORIZONTAL'] = "Barras horizontales";
$content['LN_STATS_WARNINGDISPLAY'] = "Generar gráficos en grandes fuentes de datos actualmente consume mucho tiempo. Esto se abordará en versiones posteriores. Si el procesamiento lleva demasiado tiempo, simplemente cancele la solicitud.";

// asktheoracle site
$content['LN_ORACLE_TITLE'] = "Preguntarle al oráculo por '%1'";
$content['LN_ORACLE_HELP_FOR'] = "Estos son los enlaces que el oráculo tiene para ti";
$content['LN_ORACLE_HELP_TEXT'] = "<br><h3>Le pidió al oráculo que buscara más información sobre '%1' con el valor '%2'.</h3>
<p align=\"left\">Esta página le permite hacer una búsqueda en múltiples fuentes de registro. %3<br>La idea general es facilitar la búsqueda de información sobre un tema específico en todos los lugares donde pueda existir.</p>
<p align=\"left\">Un caso de uso útil puede ser un intento de pirateo que ve en un registro web. Haga clic en la IP del atacante, que muestra esta página de búsqueda aquí. Ahora puede buscar información sobre el rango de IP y verificar sus otros registros (por ejemplo, firewall o correo) si contienen información sobre el atacante. Esperamos que esto facilite su proceso de análisis.</p>
";
	$content['LN_ORACLE_HELP_TEXT_EXTERNAL'] = "It also enables you to perform canned searches over some external databases";
$content['LN_ORACLE_HELP_DETAIL'] = "Matriz de enlaces para '%1' con el valor '%2'";
$content['LN_ORACLE_SEARCH'] = "Buscar"; // in '%1' Field";
$content['LN_ORACLE_SOURCENAME'] = "Nombre de la fuente";
$content['LN_ORACLE_FIELD'] = "Campo";
$content['LN_ORACLE_ONLINESEARCH'] = "Busqueda en Linea";
	$content['LN_ORACLE_WHOIS'] = "WHOIS Lookup para '%1' con valor '%2'";

// Report Strings
$content['LN_GEN_ERROR_INVALIDOP'] = "Tipo de operación no válida o no especificada";
$content['LN_GEN_ERROR_INVALIDREPORTID'] = "ID del informe no válido o no especificado";
$content['LN_GEN_ERROR_MISSINGSAVEDREPORTID'] = "ID del informe guardado no válido o no especificado";
$content['LN_GEN_ERROR_REPORTGENFAILED'] = "Error al generar el informe '%1' por el siguiente error: %2";
$content['LN_GEN_ERROR_WHILEREPORTGEN'] = "Se produjo un error al generar el informe"; 
$content['LN_GEN_ERROR_REPORT_NODATA'] = "No se encontraron datos para la generación de informes."; 
$content['LN_GEN_ALL_OTHER_EVENTS'] = "Todos los demás eventos";
	$content['LN_REPORT_FOOTER_ENDERED'] = "Report rendered in";
$content['LN_REPORT_FILTERS'] = "Lista de filtros usados";
$content['LN_REPORT_FILTERTYPE_DATE'] = "Fecha";
$content['LN_REPORT_FILTERTYPE_NUMBER'] = "Número";
$content['LN_REPORT_FILTERTYPE_STRING'] = "Texto";
$content['LN_GEN_SUCCESS_WHILEREPORTGEN'] = "El informe se generó correctamente";
$content['LN_GEN_ERROR_REPORTFAILEDTOGENERATE'] = "Error al generar el informe, detalles del error: %1";
$content['LN_GEN_SUCCESS_REPORTWASGENERATED_DETAILS'] = "Informe generado correctamente: %1";
$content['LN_ERROR_PATH_NOT_ALLOWED'] = "El archivo no se encuentra en la lista de directorios permitidos (por defecto solo está permitido '/var/log').";
$content['LN_ERROR_PATH_NOT_ALLOWED_EXTRA'] = "El archivo '%1' no se encuentra en uno de estos directorios: '%2'"; 
	$content['LN_CMD_RUNREPORT'] = "Generating saved report '%1'";
$content['LN_CMD_REPORTIDNOTFOUND'] = "ID de informe no válido '%1'";
$content['LN_CMD_SAVEDREPORTIDNOTFOUND'] = "ID de informe guardado no válido '%1'";
$content['LN_CMD_NOREPORTID'] = "ID de informe no especificado";
$content['LN_CMD_NOSAVEDREPORTID'] = "Falta el ID del informe guardado";
$content['LN_CMD_NOCMDPROMPT'] = "Error, este script solo se puede ejecutar desde el símbolo del sistema.";
$content['LN_REPORT_GENERATEDTIME'] = "Informe generado en: ";

	$content['LN_REPORT_ACTIONS'] = "Ejecutar Acciones del Informe";
$content['LN_REPORTS_CAT'] = "Categoría del Informe";
$content['LN_REPORTS_ID'] = "ID del Informe";
$content['LN_REPORTS_NAME'] = "Nombre del Informe";
$content['LN_REPORTS_DESCRIPTION'] = "Descripción del Informe";
$content['LN_REPORTS_HELP'] = "Ayuda";
$content['LN_REPORTS_HELP_CLICK'] = "Haga clic aquí para obtener una descripción detallada del informe.";
$content['LN_REPORTS_INFO'] = "Mostrar más Información";
	$content['LN_REPORTS_SAVEDREPORTS'] = "Informes Guardados";
$content['LN_REPORTS_ADMIN'] = "Administrar Informes";
$content['LN_REPORTMENU_LIST'] = "Lista de Informes Instalados";
$content['LN_REPORTMENU_ONLINELIST'] = "Todos los Informes Disponibles";
$content['LN_REPORTS_INFORMATION'] = "Esta página muestra una lista de informes instalados y disponibles, incluidas las configuraciones de informes guardadas.<br/>
Para ejecutar un informe, haga clic en los botones a la derecha de los informes guardados.<br/>
<b>¡Atención!</b> La generación de informes puede llevar mucho tiempo dependiendo del tamaño de su base de datos.
";
$content['LN_REPORTS_CHECKLOGSTREAMSOURCE'] = "Verifique la optimización del flujo de registro";
$content['LN_REPORTS_RUNNOW'] = "¡Ejecute el informe guardado ahora!";
$content['LN_REPORTS_ERROR_ERRORCHECKINGSOURCE'] = "Error al verificar el informe guardado Fuente: %1";

?>
