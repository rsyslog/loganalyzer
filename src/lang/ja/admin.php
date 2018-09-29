<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	*
	* Copyright (C) 2008-2010 Adiscon GmbH.
	*
	* This file is part of LogAnalyzer.
	*
	* LogAnalyzer is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	*
	* LogAnalyzer is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with LogAnalyzer. If not, see <http://www.gnu.org/licenses/>.
	*
	* A copy of the GPL can be found in the file "COPYING" in this
	* distribution.
	*********************************************************************
*/
global $content;

// Global Stuff
$content['LN_ADMINMENU_HOMEPAGE'] = "Back to Show Events";
$content['LN_ADMINMENU_GENOPT'] = "設定";
$content['LN_ADMINMENU_SOURCEOPT'] = "ソース";
$content['LN_ADMINMENU_VIEWSOPT'] = "ビュー";
$content['LN_ADMINMENU_SEARCHOPT'] = "検索";
$content['LN_ADMINMENU_USEROPT'] = "ユーザ";
$content['LN_ADMINMENU_GROUPOPT'] = "グループ";
$content['LN_ADMINMENU_CHARTOPT'] = "チャート";
$content['LN_ADMINMENU_FIELDOPT'] = "フィールド";
$content['LN_ADMINMENU_DBMAPPINGOPT'] = "DBマッピング";
$content['LN_ADMINMENU_MSGPARSERSOPT'] = "メッセージ パーサー";
$content['LN_ADMINMENU_REEPORTSOPT'] = "レポート";
$content['LN_ADMIN_CENTER'] = "管理者センター";
$content['LN_ADMIN_UNKNOWNSTATE'] = "Unknown State";
$content['LN_ADMIN_ERROR_NOTALLOWED'] = "You are not allowed to access this page with your user level.";
$content['LN_DELETEYES'] = "はい";
$content['LN_DELETENO'] = "いいえ";
$content['LN_GEN_ACTIONS'] = "利用可能なアクション";
$content['LN_ADMIN_SEND'] = "Send changes";
$content['LN_GEN_USERONLY'] = "User only";
$content['LN_GEN_USERONLYNAME'] = "User '%1'";
$content['LN_GEN_GROUPONLY'] = "Group only";
$content['LN_GEN_GLOBAL'] = "共通";
$content['LN_GEN_USERONLY_LONG'] = "For me only <br>(Only available to your user)";
$content['LN_GEN_GROUPONLY_LONG'] = "For this group <br>(Only available to the selected group)";
$content['LN_GEN_GROUPONLYNAME'] = "グループ '%1'";
$content['LN_ADMIN_POPUPHELP'] = "この機能の詳細";
$content['LN_ADMIN_DBSTATS'] = "データベース統計を表示する";
$content['LN_ADMIN_CLEARDATA'] = "古いメッセージを削除する";
$content['LN_UPDATE_AVAILABLE'] = "更新が利用可能";
$content['LN_UPDATE_INSTALLEDVER'] = "インストールされているバージョン: ";
$content['LN_UPDATE_AVAILABLEVER'] = "利用可能なバージョン: ";
$content['LN_UPDATE_LINK'] = "Click here to get the update";
$content['LN_ADMIN_RESULTREDIRECT'] = "%2秒後に <A HREF='%1'>このページ</A> へ移動します。";
$content['LN_ADMIN_RESULTCLICK'] = "続行するには <A HREF='%1'>ここ</A> をクリックしてください。";
$content['LN_ADMIN_GOBACK'] = "戻る";

// General Options
$content['LN_ADMIN_GLOBFRONTEND'] = "共通 表示オプション";
$content['LN_ADMIN_USERFRONTEND'] = "ユーザ 表示オプション";
$content['LN_ADMIN_MISC'] = "その他のオプション";
$content['LN_GEN_SHOWDEBUGMSG'] = "デバッグメッセージを表示する";
$content['LN_GEN_DEBUGGRIDCOUNTER'] = "Show Debug Gridcounter";
$content['LN_GEN_SHOWPAGERENDERSTATS'] = "Show Pagerenderstats";
$content['LN_GEN_ENABLEGZIP'] = "GZIP圧縮で出力する";
$content['LN_GEN_DEBUGUSERLOGIN'] = "Debug Userlogin";
$content['LN_GEN_WEBSTYLE'] = "初期 スタイル";
$content['LN_GEN_SELLANGUAGE'] = "初期 言語";
$content['LN_GEN_PREPENDTITLE'] = "タイトルの前に追加する文字列";
$content['LN_GEN_USETODAY'] = "日付表示に今日・昨日と表示する";
$content['LN_GEN_DETAILPOPUPS'] = "ポップアップを使用し、すべてのメッセージを表示する";
$content['LN_GEN_MSGCHARLIMIT'] = "メインビューで表示するメッセージの文字数";
$content['LN_GEN_STRCHARLIMIT'] = "Character display limit for all string type fields";
$content['LN_GEN_ENTRIESPERPAGE'] = "1ページに表示する件数";
$content['LN_GEN_AUTORELOADSECONDS'] = "自動再読み込みを有効にする(秒数)";
$content['LN_GEN_ADMINCHANGEWAITTIME'] = "Reloadtime in Adminpanel";
$content['LN_GEN_IPADRRESOLVE'] = "DNSを利用し、IPアドレスを逆引き";
$content['LN_GEN_CUSTBTNCAPT'] = "Custom search caption";
$content['LN_GEN_CUSTBTNSRCH'] = "Custom search string";
$content['LN_GEN_SUCCESSFULLYSAVED'] = "設定が保存されました。";
$content['LN_GEN_INTERNAL'] = "内部";
$content['LN_GEN_DISABLED'] = "無効";
$content['LN_GEN_CONFIGFILE'] = "設定ファイル";
$content['LN_GEN_ACCESSDENIED'] = "Access denied to this function";
$content['LN_GEN_DEFVIEWS'] = "初期 ビュー";
$content['LN_GEN_DEFSOURCE'] = "初期 ソース";
$content['LN_GEN_DEFFONT'] = "初期 フォント";
$content['LN_GEN_DEFFONTSIZE'] = "初期 フォントサイズ";
$content['LN_GEN_SUPPRESSDUPMSG'] = "重複したメッセージを表示しない";
$content['LN_GEN_TREATFILTERSTRUE'] = "Treat filters of not found fields as true";
$content['LN_GEN_INLINESEARCHICONS'] = "項目内のOnlinesearchアイコンを表示する";
$content['LN_GEN_OPTIONNAME'] = "オプション名";
$content['LN_GEN_GLOBALVALUE'] = "共通設定値";
$content['LN_GEN_PERSONALVALUE'] = "個人設定";
$content['LN_GEN_DISABLEUSEROPTIONS'] = "個人設定を無効にする";
$content['LN_GEN_ENABLEUSEROPTIONS'] = "個人設定を有効にする";
$content['LN_ADMIN_GLOBALONLY'] = "共通設定のみ";
$content['LN_GEN_DEBUGTOSYSLOG'] = "Send Debug to local syslog server";
$content['LN_GEN_POPUPMENUTIMEOUT'] = "Popupmenu Timeout in milli seconds";
$content['LN_ADMIN_SCRIPTTIMEOUT'] = "PHPスクリプトのタイムアウト(秒)";
$content['LN_GEN_INJECTHTMLHEADER'] = "&lt;head&gt; に追加する、HTMLコード";
$content['LN_GEN_INJECTBODYHEADER'] = "&lt;body&gt; の始めに追加する、HTMLコード";
$content['LN_GEN_INJECTBODYFOOTER'] = "&lt;body&gt; の終わりに追加する、HTMLコード";
$content['LN_ADMIN_PHPLOGCON_LOGOURL'] = "LogoのURL(空白の場合はデフォルトのロゴを使用)";
$content['LN_ADMIN_ERROR_READONLY'] = "This is a READONLY User, you are not allowed to perform any change operations.";
$content['LN_ADMIN_ERROR_NOTALLOWEDTOEDIT'] = "You are not allowed to edit this configuration item.";
$content['LN_ADMIN_USEPROXYSERVER'] = "Leave empty if you do not want to use a proxy server! If set to valid proxy server (for example '127.0.0.1:8080'), LogAnalyzer will use this server for remote queries like the update check feature.";
$content['LN_ADMIN_DEFAULTENCODING'] = "標準のエンコーディング"; 
$content['LN_GEN_CONTEXTLINKS'] = "Enable Contextlinks (Question marks)";
$content['LN_GEN_DISABLEADMINUSERS'] = "Disable Adminpanel for normal users";

// User Center
$content['LN_USER_CENTER'] = "User Options";
$content['LN_USER_ID'] = "ID";
$content['LN_USER_NAME'] = "Username";
$content['LN_USER_ADD'] = "Add User";
$content['LN_USER_EDIT'] = "Edit User";
$content['LN_USER_DELETE'] = "Delete User";
$content['LN_USER_PASSWORD1'] = "Password";
$content['LN_USER_PASSWORD2'] = "Confirm Password";
$content['LN_USER_ERROR_IDNOTFOUND'] = "Error, User with ID '%1' , was not found";
$content['LN_USER_ERROR_DONOTDELURSLF'] = "Error, you can not DELETE YOURSELF!";
$content['LN_USER_ERROR_DELUSER'] = "Deleting of the user with id '%1' failed!";
$content['LN_USER_ERROR_INVALIDID'] = "Error, invalid ID, User not found";
$content['LN_USER_ERROR_HASBEENDEL'] = "The User '%1' has been successfully deleted!";
$content['LN_USER_ERROR_USEREMPTY'] = "Error, Username was empty";
$content['LN_USER_ERROR_USERNAMETAKEN'] = "Error, this Username is already taken!";
$content['LN_USER_ERROR_PASSSHORT'] = "Error, Password was to short, or did not match";
$content['LN_USER_ERROR_HASBEENADDED'] = "User '%1' has been successfully added";
$content['LN_USER_ERROR_HASBEENEDIT'] = "User '%1' has been successfully edited";
$content['LN_USER_ISADMIN'] = "Is Admin?";
$content['LN_USER_ADDEDIT'] = "Add/Edit User";
$content['LN_USER_WARNREMOVEADMIN'] = "You are about to revoke your own administrative priviledges. Are you sure to remove your admin status?";
$content['LN_USER_WARNDELETEUSER'] = "Are you sure that you want to delete the User '%1'? All his personal settings will be deleted as well.";
$content['LN_USER_ERROR_INVALIDSESSIONS'] = "Invalid User Session.";
$content['LN_USER_ERROR_SETTINGFLAG'] = "Error setting flag, invalid ID or User not found";
$content['LN_USER_WARNRADYONLYADMIN'] = "You are about to set your account to readonly! This will prevent you from changing any settings! Are you sure that you want to proceed?";
$content['LN_USER_ISREADONLY'] = "Readonly User?";
$content['LN_USER_SETISADMIN'] = "Toggle IsAdmin State";
$content['LN_USER_SETISREADONLY'] = "Toggle IsReadOnly State";

// Group center
$content['LN_GROUP_CENTER'] = "Group Center";
$content['LN_GROUP_ID'] = "ID";
$content['LN_GROUP_NAME'] = "Groupname";
$content['LN_GROUP_DESCRIPTION'] = "Groupdescription";
$content['LN_GROUP_TYPE'] = "Grouptype";
$content['LN_GROUP_ADD'] = "Add Group";
$content['LN_GROUP_EDIT'] = "Edit Group";
$content['LN_GROUP_DELETE'] = "Delete Group";
$content['LN_GROUP_NOGROUPS'] = "No groups have been added yet";
$content['LN_GROUP_ADDEDIT'] = "Add/Edit Group";
$content['LN_GROUP_ERROR_GROUPEMPTY'] = "The groupname cannot be empty.";
$content['LN_GROUP_ERROR_GROUPNAMETAKEN'] = "The groupname has already been taken.";
$content['LN_GROUP_HASBEENADDED'] = "The group '%1' has been successfully added.";
$content['LN_GROUP_ERROR_IDNOTFOUND'] = "The group with ID '%1' could not be found.";
$content['LN_GROUP_ERROR_HASBEENEDIT'] = "The group '%1' has been successfully edited.";
$content['LN_GROUP_ERROR_INVALIDGROUP'] = "Error, invalid ID, Group not found";
$content['LN_GROUP_WARNDELETEGROUP'] = "Are you sure that you want to delete the Group '%1'? All Groupsettings will be deleted as well.";
$content['LN_GROUP_ERROR_DELGROUP'] = "Deleting of the group with id '%1' failed!";
$content['LN_GROUP_ERROR_HASBEENDEL'] = "The Group '%1' has been successfully deleted!";
$content['LN_GROUP_MEMBERS'] = "Groupmembers: ";
$content['LN_GROUP_ADDUSER'] = "Add User to Group";
$content['LN_GROUP_ERROR_USERIDMISSING'] = "The userid is missing.";
$content['LN_GROUP_USERHASBEENADDEDGROUP'] = "The User '%1' has been successfully added to group '%2'";
$content['LN_GROUP_ERRORNOMOREUSERS'] = "There are no more available users who can be added to the group '%1'";
$content['LN_GROUP_USER_ADD'] = "Add User to the group";
$content['LN_GROUP_USERDELETE'] = "Remove a User from the group";
$content['LN_GROUP_ERRORNOUSERSINGROUP'] = "There are no users to remove in this the group '%1'";
$content['LN_GROUP_ERROR_REMUSERFROMGROUP'] = "The user '%1' could not be removed from the group '%2'";
$content['LN_GROUP_USERHASBEENREMOVED'] = "The user '%1' has been successfully removed from the group '%2'";
$content['LN_GROUP_'] = "";

// Custom Searches center
$content['LN_SEARCH_CENTER'] = "カスタム検索";
$content['LN_SEARCH_ADD'] = "新しいカスタム検索を追加する";
$content['LN_SEARCH_ID'] = "ID";
$content['LN_SEARCH_NAME'] = "検索名";
$content['LN_SEARCH_QUERY'] = "検索クエリ";
$content['LN_SEARCH_TYPE'] = "割り当て";
$content['LN_SEARCH_EDIT'] = "編集";
$content['LN_SEARCH_DELETE'] = "削除";
$content['LN_SEARCH_ADDEDIT'] = "カスタム検索の追加・削除";
$content['LN_SEARCH_SELGROUPENABLE'] = ">> Select Group to enable <<";
$content['LN_SEARCH_ERROR_DISPLAYNAMEEMPTY'] = "表示名は空白に出来ません。";
$content['LN_SEARCH_ERROR_SEARCHQUERYEMPTY'] = "検索クエリは空白に出来ません。";
$content['LN_SEARCH_HASBEENADDED'] = "カスタム検索 '%1' が追加されました。";
$content['LN_SEARCH_ERROR_IDNOTFOUND'] = "指定された検索ID '%1' が見つかりませんでした。";
$content['LN_SEARCH_ERROR_INVALIDID'] = "無効な検索IDです。";
$content['LN_SEARCH_HASBEENEDIT'] = "カスタム検索 '%1' が保存されました。";
$content['LN_SEARCH_WARNDELETESEARCH'] = "カスタム検索 '%1' を削除してもよろしいですか？(元に戻すことは出来ません)";
$content['LN_SEARCH_ERROR_DELSEARCH'] = "カスタム検索ID '%1' の削除に失敗しました。";
$content['LN_SEARCH_ERROR_HASBEENDEL'] = "カスタム検索 '%1' が削除されました。";
$content['LN_SEARCH_'] = "";

// Custom Views center
$content['LN_VIEWS_CENTER'] = "ビュー オプション";
$content['LN_VIEWS_ID'] = "ID";
$content['LN_VIEWS_NAME'] = "ビュー名";
$content['LN_VIEWS_COLUMNS'] = "表示列";
$content['LN_VIEWS_TYPE'] = "割り当て";
$content['LN_VIEWS_ADD'] = "新しいビューを追加する";
$content['LN_VIEWS_EDIT'] = "編集";
$content['LN_VIEWS_ERROR_IDNOTFOUND'] = "指定されたビューID '%1' が見つかりませんでした。";
$content['LN_VIEWS_ERROR_INVALIDID'] = "無効なビューID '%1' です。";
$content['LN_VIEWS_WARNDELETEVIEW'] = "ビュー '%1' を削除してもよろしいですか？(元に戻すことは出来ません)";
$content['LN_VIEWS_ERROR_DELSEARCH'] = "ビューID '%1' の削除に失敗しました。";
$content['LN_VIEWS_ERROR_HASBEENDEL'] = "ビュー '%1' が削除されました。";
$content['LN_VIEWS_ADDEDIT'] = "ビューの追加・編集";
$content['LN_VIEWS_COLUMNLIST'] = "設定済みの列";
$content['LN_VIEWS_ADDCOLUMN'] = "列を追加する";
$content['LN_VIEWS_ERROR_DISPLAYNAMEEMPTY'] = "表示名は空白に出来ません。";
$content['LN_VIEWS_COLUMN'] = "列";
$content['LN_VIEWS_COLUMN_REMOVE'] = "列を削除する";
$content['LN_VIEWS_HASBEENADDED'] = "ビュー '%1' が追加されました";
$content['LN_VIEWS_ERROR_NOCOLUMNS'] = "You need to add at least one column in order to add a new Custom View.";
$content['LN_VIEWS_HASBEENEDIT'] = "ビュー '%1' が保存されました。";
$content['LN_VIEWS_'] = "";

// Custom DBMappings center
$content['LN_DBMP_CENTER'] = "データベース フィールドマッピング オプション";
$content['LN_DBMP_ID'] = "ID";
$content['LN_DBMP_NAME'] = "データベースマッピング名";
$content['LN_DBMP_DBMAPPINGS'] = "データベース マッピング";
$content['LN_DBMP_ADD'] = "新しいデータベースマッピングを追加する";
$content['LN_DBMP_EDIT'] = "編集";
$content['LN_DBMP_DELETE'] = "削除";
$content['LN_DBMP_ERROR_IDNOTFOUND'] = "指定されたデータベースマッピングID '%1' が見つかりませんでした。";
$content['LN_DBMP_ERROR_INVALIDID'] = "無効なデータベースマッピングID '%1' です。";
$content['LN_DBMP_WARNDELETEMAPPING'] = "データベースマッピング '%1' を削除してもよろしいですか？(元に戻すことは出来ません)";
$content['LN_DBMP_ERROR_DELSEARCH'] = "データベースマッピングID '%1' の削除に失敗しました。";
$content['LN_DBMP_ERROR_HASBEENDEL'] = "データベースマッピング '%1' が削除されました。";
$content['LN_DBMP_ADDEDIT'] = "データベースマッピングの追加・編集";
$content['LN_DBMP_DBMAPPINGSLIST'] = "設定済みのマッピング";
$content['LN_DBMP_ADDMAPPING'] = "マッピングを追加する";
$content['LN_DBMP_ERROR_DISPLAYNAMEEMPTY'] = "表示名は空白に出来ません。";
$content['LN_DBMP_MAPPING'] = "マッピング";
$content['LN_DBMP_MAPPING_MOVEUP'] = "上へ";
$content['LN_DBMP_MAPPING_MOVEDOWN'] = "下へ";
$content['LN_DBMP_MAPPING_REMOVE'] = "削除";
$content['LN_DBMP_MAPPING_EDIT'] = "編集";
$content['LN_DBMP_HASBEENADDED'] = "データベースマッピング '%1' が追加されました。";
$content['LN_DBMP_ERROR_NOCOLUMNS'] = "You need to add at least one column in order to add a new Custom Database Mapping.";
$content['LN_DBMP_HASBEENEDIT'] = "データベースマッピング '%1' が保存されました。";
$content['LN_DBMP_ERROR_MISSINGFIELDNAME'] = "Missing mapping for the '%1' field.";
$content['LN_SOURCES_FILTERSTRING'] = "Custom Searchfilter";
$content['LN_SOURCES_FILTERSTRING_HELP'] = "Use the same syntax as in the search field. For example if you want to show only messages from 'server1', use this searchfilter: source:=server1";

// Custom Sources center
$content['LN_SOURCES_CENTER'] = "ソース オプション";
$content['LN_SOURCES_EDIT'] = "編集";
$content['LN_SOURCES_DELETE'] = "削除";
$content['LN_SOURCES_ID'] = "ID";
$content['LN_SOURCES_NAME'] = "ソース名";
$content['LN_SOURCES_TYPE'] = "ソース種別";
$content['LN_SOURCES_ASSIGNTO'] = "割り当て";
$content['LN_SOURCES_DISK'] = "Diskfile";
$content['LN_SOURCES_DB'] = "MySQL データベース";
$content['LN_SOURCES_CLICKHOUSE'] = "ClickHouse";
$content['LN_SOURCES_PDO'] = "PDO データソース";
$content['LN_SOURCES_MONGODB'] = "MongoDB データソース";
$content['LN_SOURCES_ADD'] = "新しいソースを追加する";
$content['LN_SOURCES_ADDEDIT'] = "ソースの追加・編集";
$content['LN_SOURCES_TYPE'] = "ソース種別";
$content['LN_SOURCES_DISKTYPEOPTIONS'] = "ファイル オプション";
$content['LN_SOURCES_ERROR_MISSINGPARAM'] = "The paramater '%1' is missing.";
$content['LN_SOURCES_ERROR_NOTAVALIDFILE'] = "Failed to open the syslog file '%1'! Check if the file exists and LogAnalyzer has sufficient rights to it";
$content['LN_SOURCES_ERROR_UNKNOWNSOURCE'] = "不明なソース '%1' が検出されました。";
$content['LN_SOURCE_HASBEENADDED'] = "ソース '%1' が追加されました。";
$content['LN_SOURCES_EDIT'] = "編集";
$content['LN_SOURCES_ERROR_INVALIDORNOTFOUNDID'] = "無効なソースIDです。";
$content['LN_SOURCES_ERROR_IDNOTFOUND'] = "指定されたソースIDが見つかりませんでした。";
$content['LN_SOURCES_HASBEENEDIT'] = "The Source '%1' has been successfully edited.";
$content['LN_SOURCES_WARNDELETESEARCH'] = "ソース '%1' を削除してもよろしいですか？(元に戻すことは出来ません)";
$content['LN_SOURCES_ERROR_DELSOURCE'] = "ソースID '%1' の削除に失敗しました。";
$content['LN_SOURCES_ERROR_HASBEENDEL'] = "ソース '%1' が削除されました。";
$content['LN_SOURCES_DESCRIPTION'] = "ソースの説明(オプション)";
$content['LN_SOURCES_ERROR_INVALIDVALUE'] = "Invalid value for the paramater '%1'.";
$content['LN_SOURCES_STATSNAME'] = "名前";
$content['LN_SOURCES_STATSVALUE'] = "値	";
$content['LN_SOURCES_DETAILS'] = "ログストリーム ソースの詳細";
$content['LN_SOURCES_STATSDETAILS'] = "ログストリーム ソースの統計";
$content['LN_SOURCES_ERROR_NOSTATSDATA'] = "Could not find or obtain any stats related information for this logstream source.";
$content['LN_SOURCES_ERROR_NOCLEARSUPPORT'] = "This logstream source does not support deleting data.";
$content['LN_SOURCES_ROWCOUNT'] = "総行数";
$content['LN_SOURCES_CLEARDATA'] = "データベースの保守オプション";
$content['LN_SOURCES_CLEAROPTIONS'] = "データの消去方法を選択します。";
$content['LN_SOURCES_CLEARALL'] = "すべてのデータ";
$content['LN_SOURCES_CLEAR_HELPTEXT'] = "注意！ データの削除に注意してください。ここで実行された操作は元に戻すことはできません！";
$content['LN_SOURCES_CLEARSINCE'] = "これ以前のデータを削除する： ";
$content['LN_SOURCES_CLEARDATE'] = "これ以前のデータを削除する： ";
$content['LN_SOURCES_CLEARDATA_SEND'] = "選択された範囲のデータを削除する";
$content['LN_SOURCES_ERROR_INVALIDCLEANUP'] = "無効なデータ消去方法です。";
$content['LN_SOURCES_WARNDELETEDATA'] = "データソース '%1' からデータを削除してもよろしいですか？(元に戻すことは出来ません)";
$content['LN_SOURCES_ERROR_DELDATA'] = "Could not delete data in the '%1' source";
$content['LN_SOURCES_HASBEENDELDATA'] = "データソース '%1' から '%2' 行のデータを削除しました。";

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
$content['LN_CHARTS_CENTER'] = "チャート オプション";
$content['LN_CHARTS_EDIT'] = "編集";
$content['LN_CHARTS_DELETE'] = "削除";
$content['LN_CHARTS_ADD'] = "新しいチャートを追加する";
$content['LN_CHARTS_ADDEDIT'] = "チャートの追加・削除";
$content['LN_CHARTS_NAME'] = "チャート名";
$content['LN_CHARTS_ENABLED'] = "有効";
$content['LN_CHARTS_ENABLEDONLY'] = "有効";
$content['LN_CHARTS_ERROR_INVALIDORNOTFOUNDID'] = "The Chart-ID is invalid or could not be found.";
$content['LN_CHARTS_WARNDELETESEARCH'] = "Are you sure that you want to delete the Chart '%1'? This cannot be undone!";
$content['LN_CHARTS_ERROR_DELCHART'] = "Deleting of the Chart with id '%1' failed!";
$content['LN_CHARTS_ERROR_HASBEENDEL'] = "The Chart '%1' has been successfully deleted!";
$content['LN_CHARTS_ERROR_MISSINGPARAM'] = "The paramater '%1' is missing.";
$content['LN_CHARTS_HASBEENADDED'] = "The new Chart '%1' has been successfully added.";
$content['LN_CHARTS_ERROR_IDNOTFOUND'] = "The Chart-ID could not be found in the database.";
$content['LN_CHARTS_HASBEENEDIT'] = "The Chart '%1' has been successfully edited.";
$content['LN_CHARTS_ID'] = "ID";
$content['LN_CHARTS_ASSIGNTO'] = "割り当て";
$content['LN_CHARTS_PREVIEW'] = "Preview Chart in a new Window";
$content['LN_CHARTS_FILTERSTRING'] = "Custom Filter";
$content['LN_CHARTS_FILTERSTRING_HELP'] = "Use the same syntax as in the search field. For example if you want to generate a chart for 'server1', use this filter: source:=server1";
$content['LN_CHARTS_ERROR_CHARTIDNOTFOUND'] = "Error, ChartID with ID '%1' , was not found";
$content['LN_CHARTS_ERROR_SETTINGFLAG'] = "Error setting flag, invalid ChartID or operation.";
$content['LN_CHARTS_SETENABLEDSTATE'] = "Toggle Enabled State";

// Fields Options
$content['LN_FIELDS_CENTER'] = "フィールド オプション";
$content['LN_FIELDS_EDIT'] = "編集";
$content['LN_FIELDS_DELETE'] = "削除";
$content['LN_FIELDS_ADD'] = "フィールドを追加する";
$content['LN_FIELDS_ID'] = "フィールドID";
$content['LN_FIELDS_NAME'] = "表示名";
$content['LN_FIELDS_DEFINE'] = "内部 フィールドID";
$content['LN_FIELDS_DELETE_FROMDB'] = "Delete Field from DB";
$content['LN_FIELDS_ADDEDIT'] = "フィールドの追加・削除";
$content['LN_FIELDS_TYPE'] = "フィールド種別";
$content['LN_FIELDS_ALIGN'] = "リストビューでの表示位置";
$content['LN_FIELDS_SEARCHONLINE'] = "オンラインサーチを有効にする";
$content['LN_FIELDS_DEFAULTWIDTH'] = "リストビューでの幅";
$content['LN_FIELDS_ERROR_IDNOTFOUND'] = "The Field-ID could not be found in the database, or in the default constants.";
$content['LN_FIELDS_ERROR_INVALIDID'] = "The Field with ID '%1' is not a valid Field.";
$content['LN_FIELDS_SEARCHFIELD'] = "Searchfilterの名前";
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
$content['LN_ALIGN_CENTER'] = "中央";
$content['LN_ALIGN_LEFT'] = "左寄せ";
$content['LN_ALIGN_RIGHT'] = "右寄せ";
$content['LN_FILTER_TYPE_STRING'] = "文字列";
$content['LN_FILTER_TYPE_NUMBER'] = "数値";
$content['LN_FILTER_TYPE_DATE'] = "日付";

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
