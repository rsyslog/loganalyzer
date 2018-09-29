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
$content['LN_MAINTITLE'] = "Main LogAnalyzer";
$content['LN_MAIN_SELECTSTYLE'] = "スタイルを選択";
$content['LN_GEN_LANGUAGE'] = "言語を選択";
$content['LN_GEN_SELECTSOURCE'] = "ソースを選択";
$content['LN_GEN_MOREPAGES'] = "More than one Page available";
$content['LN_GEN_FIRSTPAGE'] = "最初のページ";
$content['LN_GEN_LASTPAGE'] = "最後のページ";
$content['LN_GEN_NEXTPAGE'] = "次のページ";
$content['LN_GEN_PREVIOUSPAGE'] = "前のページ";
$content['LN_GEN_RECORDCOUNT'] = "Total records found";
$content['LN_GEN_PAGERSIZE'] = "1ページの件数";
$content['LN_GEN_PAGE'] = "ページ";
$content['LN_GEN_PREDEFINEDSEARCHES'] = "Predefined Searches";
$content['LN_GEN_SOURCE_DISK'] = "ファイル";
$content['LN_GEN_SOURCE_DB'] = "MySQL ネイティブ";
$content['LN_GEN_SOURCE_CLICKHOUSE'] = "ClickHouse";
$content['LN_GEN_SOURCE_PDO'] = "データベース (PDO)";
$content['LN_GEN_SOURCE_MONGODB'] = "MongoDB ネイティブ";
$content['LN_GEN_RECORDSPERPAGE'] = "件";
$content['LN_GEN_PRECONFIGURED'] = "初期値";
$content['LN_GEN_AVAILABLESEARCHES'] = "利用可能な検索条件";
$content['LN_GEN_DB_MYSQL'] = "MySQLサーバ";
$content['LN_GEN_DB_MSSQL'] = "Microsoft SQL Server";
$content['LN_GEN_DB_ODBC'] = "ODBC Database Source";
$content['LN_GEN_DB_PGSQL'] = "PostgreSQL";
$content['LN_GEN_DB_OCI'] = "Oracle Call Interface";
$content['LN_GEN_DB_DB2'] = "	IBM DB2";
$content['LN_GEN_DB_FIREBIRD'] = "Firebird/Interbase 6";
$content['LN_GEN_DB_INFORMIX'] = "IBM Informix Dynamic Server";
$content['LN_GEN_DB_SQLITE'] = "SQLite 2";
$content['LN_GEN_SELECTVIEW'] = "ビューを選択";
$content['LN_GEN_CRITERROR_UNKNOWNTYPE'] = "The source type '%1' is not supported by LogAnalyzer yet. This is a critical error, please fix your configuration.";
$content['LN_GEN_ERRORRETURNPREV'] = "クリックして、前のページに戻る。";
$content['LN_GEN_ERRORDETAILS'] = "エラー 詳細:";
$content['LN_SOURCES_ERROR_WITHINSOURCE'] = "The source '%1' checking returned with an error:<br>%2";
$content['LN_SOURCES_ERROR_EXTRAMSG'] = "Extra Error Details:<br>%1";
$content['LN_ERROR_NORECORDS'] = "syslogレコードがありません。";
$content['LN_ERROR_FILE_NOT_FOUND'] = "Syslog file could not be found";
$content['LN_ERROR_FILE_NOT_READABLE'] = "Syslog file is not readable, read access may be denied";
$content['LN_ERROR_UNKNOWN'] = "Unknown or unhandled error occured (Error Code '%1')";
$content['LN_ERROR_FILE_EOF'] = "End of File reached";
$content['LN_ERROR_FILE_BOF'] = "Begin of File reeached";
$content['LN_ERROR_FILE_CANT_CLOSE'] = "ファイルを閉じる事が出来ません。";
$content['LN_ERROR_UNDEFINED'] = "Undefined Error";
$content['LN_ERROR_EOS'] = "End of stream reached";
$content['LN_ERROR_FILTER_NOT_MATCH'] = "Filter does not match any results";
$content['LN_ERROR_DB_CONNECTFAILED'] = "Connection to the database server failed";
$content['LN_ERROR_DB_CANNOTSELECTDB'] = "Could not find the configured database";
$content['LN_ERROR_DB_QUERYFAILED'] = "Dataquery failed to execute";
$content['LN_ERROR_DB_NOPROPERTIES'] = "No database properties found";
$content['LN_ERROR_DB_INVALIDDBMAPPING'] = "Invalid datafield mappings";
$content['LN_ERROR_DB_INVALIDDBDRIVER'] = "Invalid database driver selected";
$content['LN_ERROR_DB_TABLENOTFOUND'] = "Could not find the configured table, maybe misspelled or the tablenames are case sensitive";
$content['LN_ERROR_DB_DBFIELDNOTFOUND'] = "Database Field mapping for at least one field could not be found.";
$content['LN_GEN_SELECTEXPORT'] = "&gt; エクスポート形式の選択 &lt;";
$content['LN_GEN_EXPORT_CVS'] = "CSV (カンマ区切り)";
$content['LN_GEN_EXPORT_XML'] = "XML";
$content['LN_GEN_EXPORT_PDF'] = "PDF";
$content['LN_GEN_ERROR_EXPORING'] = "Error exporting data";
$content['LN_GEN_ERROR_INVALIDEXPORTTYPE'] = "Invalid Export format selected, or other parameters were wrong.";
$content['LN_GEN_ERROR_SOURCENOTFOUND'] = "The Source with ID '%1' could not be found.";
$content['LN_GEN_MOREINFORMATION'] = "詳細情報";
$content['LN_FOOTER_PAGERENDERED'] = "ページ生成時間";
$content['LN_FOOTER_DBQUERIES'] = "DBクエリ";
$content['LN_FOOTER_GZIPENABLED'] = "GZIP有効";
$content['LN_FOOTER_SCRIPTTIMEOUT'] = "スクリプトタイムアウト";
$content['LN_FOOTER_SECONDS'] = "秒";
$content['LN_WARNING_LOGSTREAMTITLE'] = "Logstream Warning";
$content['LN_WARNING_LOGSTREAMDISK_TIMEOUT'] = "While reading the logstream, the php script timeout forced me to abort at this point.<br><br> If you want to avoid this, please increase the LogAnalyzer script timeout in your config.php. If the user system is installed, you can do that in Admin center.";
$content['LN_ERROR_FILE_NOMORETIME'] = "No more time for processing left";
$content['LN_WARNING_DBUPGRADE'] = "Database Upgrade required";
$content['LN_WARNING_DBUPGRADE_TEXT'] = "The current installed database version is '%1'.<br>An update to version '%2' is available.";
$content['LN_ERROR_REDIRECTABORTED'] = 'Automatic redirect to the <a href="%1">page</a> was aborted, as an internal error occured. Please see the error details above and contact our support forums if you need assistance.';
$content['LN_DEBUGLEVEL'] = "デバッグレベル";
$content['LN_DEBUGMESSAGE'] = "デバッグメッセージ";
$content['LN_GEN_REPORT_OUTPUT_HTML'] = "HTML形式";
$content['LN_GEN_REPORT_OUTPUT_PDF'] = "PDF形式";
$content['LN_GEN_REPORT_TARGET_STDOUT'] = "標準出力";
$content['LN_GEN_REPORT_TARGET_FILE'] = "ファイルに保存";
$content['LN_GEN_REPORT_TARGET_EMAIL'] = "メールで送信";
$content['LN_GEN_UNKNOWN'] = "不明";
$content['LN_GEN_AUTH_INTERNAL'] = "内部認証";
$content['LN_GEN_AUTH_LDAP'] = "LDAP認証";

// Topmenu Entries
$content['LN_MENU_SEARCH'] = "検索";
$content['LN_MENU_SHOWEVENTS'] = "イベントを表示";
$content['LN_MENU_HELP'] = "ヘルプ";
	$content['LN_MENU_DOC'] = "ドキュメント";
	$content['LN_MENU_FORUM'] = "サポート";
	$content['LN_MENU_WIKI'] = "LogAnalyzer Wiki";
	$content['LN_MENU_PROSERVICES'] = "Professional Services";
$content['LN_MENU_SEARCHINKB'] = "Knowledge Baseを検索";
$content['LN_MENU_LOGIN'] = "ログイン";
$content['LN_MENU_ADMINCENTER'] = "管理センター";
$content['LN_MENU_LOGOFF'] = "ログオフ";
$content['LN_MENU_LOGGEDINAS'] = "ログイン中：";
$content['LN_MENU_MAXVIEW'] = "最大表示";
$content['LN_MENU_NORMALVIEW'] = "通常表示";
$content['LN_MENU_STATISTICS'] = "統計";
$content['LN_MENU_CLICKTOEXPANDMENU'] = "Click the icon to show the menu";
	$content['LN_MENU_REPORTS'] = "レポート";

// Main Index Site
$content['LN_ERROR_INSTALLFILEREMINDER'] = "Warning! You still have NOT removed the 'install.php' from your LogAnalyzer main directory!";
$content['LN_TOP_NUM'] = "No.";
$content['LN_TOP_UID'] = "uID";
$content['LN_GRID_POPUPDETAILS'] = "Details for Syslogmessage with ID '%1'";

$content['LN_SEARCH_USETHISBLA'] = "Use the form below and your advanced search will appear here";
$content['LN_SEARCH_FILTER'] = "条件:";
$content['LN_SEARCH_ADVANCED'] = "詳細検索";
$content['LN_SEARCH'] = "検索";
$content['LN_SEARCH_RESET'] = "リセット";
$content['LN_SEARCH_PERFORMADVANCED'] = "Perform Advanced Search";
$content['LN_VIEW_MESSAGECENTERED'] = "Back to unfiltered view with this message at top";
$content['LN_VIEW_RELATEDMSG'] = "View related syslog messages";
$content['LN_VIEW_FILTERFOR'] = "Filter message for ";
$content['LN_VIEW_SEARCHFOR'] = "Search online for ";
$content['LN_VIEW_SEARCHFORGOOGLE'] = "Search Google for ";
$content['LN_GEN_MESSAGEDETAILS'] = "Message Details";
	$content['LN_VIEW_ADDTOFILTER'] = "Add '%1' to filterset";
	$content['LN_VIEW_EXCLUDEFILTER'] = "Exclude '%1' from filterset";
	$content['LN_VIEW_FILTERFORONLY'] = "Filter for '%1' only";
	$content['LN_VIEW_SHOWALLBUT'] = "Show all except '%1'";
	$content['LN_VIEW_VISITLINK'] = "Open Link '%1' in new window";

$content['LN_HIGHLIGHT'] = "強調表示 >>";
$content['LN_HIGHLIGHT_OFF'] = "Highlight <<";
$content['LN_HIGHLIGHT_WORDS'] = "強調表示したい文字をカンマ区切りで入力";

$content['LN_AUTORELOAD'] = "自動更新";
$content['LN_AUTORELOAD_DISABLED'] = "無効";
$content['LN_AUTORELOAD_PRECONFIGURED'] = "Preconfigured auto reload ";
$content['LN_AUTORELOAD_SECONDS'] = "秒";
$content['LN_AUTORELOAD_MINUTES'] = "分";

// Filter Options
$content['LN_FILTER_DATE'] = "日時の範囲";
$content['LN_FILTER_DATEMODE'] = "モード";
$content['LN_DATEMODE_ALL'] = "すべての日時";
$content['LN_DATEMODE_RANGE'] = "日時の範囲";
$content['LN_DATEMODE_LASTX'] = "直近 xx";
$content['LN_FILTER_DATEFROM'] = "開始日";
$content['LN_FILTER_DATETO'] = "終了日";
$content['LN_FILTER_TIMEFROM'] = "開始時間";
$content['LN_FILTER_TIMETO'] = "終了時間";
$content['LN_FILTER_DATELASTX'] = "日付の範囲";
$content['LN_FILTER_ADD2SEARCH'] = "Add to search";
$content['LN_DATE_LASTX_HOUR'] = "最後の1時間";
$content['LN_DATE_LASTX_12HOURS'] = "直近 12時間";
$content['LN_DATE_LASTX_24HOURS'] = "直近 24時間";
$content['LN_DATE_LASTX_7DAYS'] = "直近 7日間";
$content['LN_DATE_LASTX_31DAYS'] = "直近 31日間";
$content['LN_FILTER_FACILITY'] = "Syslog Facility";
$content['LN_FILTER_SEVERITY'] = "Syslog Severity";
$content['LN_FILTER_OTHERS'] = "その他のフィルタ";
$content['LN_FILTER_MESSAGE'] = "Syslog メッセージ";
$content['LN_FILTER_SYSLOGTAG'] = "Syslog タグ";
$content['LN_FILTER_SOURCE'] = "ソース (ホスト名)";
$content['LN_FILTER_MESSAGETYPE'] = "メッセージタイプ";

// Install Page
$content['LN_CFG_DBSERVER'] = "データベース ホスト";
$content['LN_CFG_DBPORT'] = "データベース ポート";
$content['LN_CFG_DBNAME'] = "データベース名";
$content['LN_CFG_DBPREF'] = "テーブル プレフィックス";
$content['LN_CFG_DBUSER'] = "データベース ユーザ名";
$content['LN_CFG_DBPASSWORD'] = "データベース パスワード";
$content['LN_CFG_PARAMMISSING'] = "The following parameter were missing: ";
$content['LN_CFG_SOURCETYPE'] = "ソース種別";
$content['LN_CFG_DISKTYPEOPTIONS'] = "ディスクタイプ オプション";
$content['LN_CFG_LOGLINETYPE'] = "Syslog ファイル種別";
$content['LN_CFG_SYSLOGFILE'] = "Syslog ファイル";
$content['LN_CFG_DATABASETYPEOPTIONS'] = "データベース オプション";
$content['LN_CFG_DBTABLETYPE'] = "テーブルタイプ";
$content['LN_CFG_DBSTORAGEENGINE'] = "データベース ストレージ エンジン";
$content['LN_CFG_DBTABLENAME'] = "データベース テーブル名";
$content['LN_CFG_NAMEOFTHESOURCE'] = "ソースの名前";
$content['LN_CFG_FIRSTSYSLOGSOURCE'] = "1つ目のSyslogソース";
$content['LN_CFG_DBROWCOUNTING'] = "行カウントを有効にする";
$content['LN_CFG_VIEW'] = "ビュー";
$content['LN_CFG_DBUSERLOGINREQUIRED'] = "ユーザにログインを要求しますか";
$content['LN_CFG_MSGPARSERS'] = "メッセージパーサー(カンマ区切り)";
$content['LN_CFG_NORMALIZEMSG'] = "Normalize Message within Parsers";
$content['LN_CFG_SKIPUNPARSEABLE'] = "パース出来なかったメッセージをスキップする(メッセージパーサーが設定されている場合のみ動作)";
$content['LN_CFG_DBRECORDSPERQUERY'] = "Recordcount for database queries";
$content['LN_CFG_LDAPServer'] = "LDAPサーバ(ホスト名/IPアドレス)";
$content['LN_CFG_LDAPPort'] = "LDAP Port, default 389 (636 for SSL)";
$content['LN_CFG_LDAPBaseDN'] = "Base DN for LDAP Search";
$content['LN_CFG_LDAPSearchFilter'] = "Basic Search filter";
$content['LN_CFG_LDAPUidAttribute'] = "LDAP Username attribute";
$content['LN_CFG_LDAPBindDN'] = "Privilegied user used to LDAP queries";
$content['LN_CFG_LDAPBindPassword'] = "Password of the privilegied user";
$content['LN_CFG_LDAPDefaultAdminUser'] = "Default administrative LDAP Username";
$content['LN_CFG_AUTHTYPE'] = "認証種別";
$content['LN_GEN_AUTH_LDAP_OPTIONS'] = "LDAP認証オプション";

// Details page
$content['LN_DETAILS_FORSYSLOGMSG'] = "Details for the syslog messages with id";
$content['LN_DETAILS_DETAILSFORMSG'] = "Details for message id";
$content['LN_DETAIL_BACKTOLIST'] = "Back to Listview";
$content['LN_DETAIL_DYNAMIC_FIELDS'] = "Dynamic fields";


// Login Site
$content['LN_LOGIN_DESCRIPTION'] = "LogAnalyzerを利用するにはログインしてください。";
$content['LN_LOGIN_TITLE'] = "ログイン";
$content['LN_LOGIN_USERNAME'] = "ユーザ名";
$content['LN_LOGIN_PASSWORD'] = "パスワード";
$content['LN_LOGIN_SAVEASCOOKIE'] = "ログインを保持する";
$content['LN_LOGIN_ERRWRONGPASSWORD'] = "ユーザ名もしくはパスワードが間違っています。";
$content['LN_LOGIN_USERPASSMISSING'] = "ユーザ名もしくはパスワードが入力されていません。";
$content['LN_LOGIN_LDAP_USERNOTFOUND'] = "ユーザ '%1' は登録されていません。";
$content['LN_LOGIN_LDAP_USERCOULDNOTLOGIN'] = "'%1' ではログインできません。 LDAP error: %2";
$content['LN_LOGIN_LDAP_PASSWORDFAIL'] = "'%1' は入力されたパスワードでログインできませんでした。";
$content['LN_LOGIN_LDAP_SERVERFAILED'] = "LDAPサーバに接続出来ませんでした。 '%1'";
$content['LN_LOGIN_LDAP_USERBINDFAILED'] = "Could not bind with the Search user DN '%1'";


// Install Site
$content['LN_INSTALL_TITLETOP'] = "LogAnalyzer Version %1 のインストール - ステップ %2";
$content['LN_INSTALL_TITLE'] = "インストール ステップ %1";
$content['LN_INSTALL_ERRORINSTALLED'] = 'LogAnalyzerはすでに設定されています。<br><br>もし、LogAnalyzerを再設定したい場合は、<B>config.php</B>を削除するか、空ファイルに置き換えてください。<br><br><A HREF="index.php">ここ</A>をクリックすると、スタートページに戻ります。';
$content['LN_INSTALL_FILEORDIRNOTWRITEABLE'] = "少なくとも1つのファイルまたはディレクトリが書き込み可能ではありません。ファイルのパーミッションを確認してください。(chmod 666)!";
$content['LN_INSTALL_SAMPLECONFIGMISSING'] = "このサンプル設定には問題があります。 '%1' LogAnalyzerが完全にアップロードされていません。";
$content['LN_INSTALL_ERRORCONNECTFAILED'] = "データベース '%1' に接続出来ませんでした。サーバ名、ポート、ユーザ名やパスワードを確認してください。";
$content['LN_INSTALL_ERRORACCESSDENIED'] = "データベース '%1' へのアクセスが拒否されました。 データベースが存在しないか、アクセス権を確認してください。";
$content['LN_INSTALL_ERRORINVALIDDBFILE'] = "エラー： データベース定義ファイル '%1' が無効です(短すぎる) ファイルのアップロードに失敗していないか確認してください。";
$content['LN_INSTALL_ERRORINSQLCOMMANDS'] = "エラー： データベース定義ファイル '%1' が無効です(SQL文が存在しまい)<br> ファイルのアップロードに失敗していないか、LogAnalyzer フォーラムに支援を求めてください。";
$content['LN_INSTALL_MISSINGUSERNAME'] = "ユーザー名を指定する必要があります";
$content['LN_INSTALL_PASSWORDNOTMATCH'] = "パスワードが一致しないか、もしくは短すぎます。";
$content['LN_INSTALL_FAILEDTOOPENSYSLOGFILE'] = "Syslogファイル '%1' が開けませんでした。ファイルが存在し、権限に問題が無いか確認してください。<br>";
$content['LN_INSTALL_FAILEDCREATECFGFILE'] = "設定ファイルが '%1' に作成出来ませんでした。権限を確認してください。";
$content['LN_INSTALL_FAILEDREADINGFILE'] = "'%1' が読み込めませんでした。ファイルが存在するか確認してください。";
$content['LN_INSTALL_ERRORREADINGDBFILE'] = "標準データベース定義ファイル '%1' が読み込めません。ファイルが存在するか確認してください。";
$content['LN_INSTALL_STEP1'] = "ステップ 1 - 前提条件";
$content['LN_INSTALL_STEP2'] = "ステップ 2 - ファイルパーミッションの検証";
$content['LN_INSTALL_STEP3'] = "ステップ 3 - 基本設定";
$content['LN_INSTALL_STEP4'] = "ステップ 4 - テーブルの作成";
$content['LN_INSTALL_STEP5'] = "ステップ 5 - SQL結果の確認";
$content['LN_INSTALL_STEP6'] = "ステップ 6 - メインユーザの作成";
$content['LN_INSTALL_STEP7'] = "ステップ 7 - データソースの作成";
$content['LN_INSTALL_STEP8'] = "ステップ 8 - 完了";
$content['LN_INSTALL_STEP1_TEXT'] = 'LogAnalyzerのインストールを開始する前に、このインストーラーでいくつかの確認を行います。<br>最初にいくつかのファイルのパーミッションを修正する必要があるかもしれません。<br><br><input type="submit" value="Next">をクリックして、確認を始めましょう！';
$content['LN_INSTALL_STEP2_TEXT'] = "ファイルのパーミッションをチェックしています。結果は次の内容を確認してください。<br><B>contrib</B> フォルダーの <B>configure.sh</B> スクリプトを使って設定することも出来ます。";
$content['LN_INSTALL_STEP3_TEXT'] = "LogAnalyzerの基本的な設定を行います。";
$content['LN_INSTALL_STEP4_TEXT'] = 'データベースとの接続が確認出来ました。<br><br>過ぎのステップではLogAnalyzerで使用されるデータベースを作成します。<br> <b>警告</b> もし、すでにLogAnalyzerのデータベースが同じテーブルプレフィックスでインストールされている場合、すべて <b>上書きされます</b>! 新しいデータベースを使用している事を確認するか、古いLogAnalyzerデータベースを上書きします。 <br><br><b><input type="submit" value="Next">をクリックすると、テーブルの作成が開始されます。</b>';
$content['LN_INSTALL_STEP5_TEXT'] = "テーブルが作成されました。 Check the List below for possible Error's";
$content['LN_INSTALL_STEP6_TEXT'] = "You are now about to create the initial LogAnalyzer User Account.<br> This will be the first administrative user, which will be needed to login into LogAnalyzer and access the Admin Center!";
$content['LN_INSTALL_STEP8_TEXT'] = 'おめてとうございます！ LogAnalyzer のインストールに成功しました。<br><br><a href="index.php">ここ</a> をクリックして、インストールを完了してください。';
$content['LN_INSTALL_PROGRESS'] = "進捗状況: ";
$content['LN_INSTALL_FRONTEND'] = "フロントエンドオプション";
$content['LN_INSTALL_NUMOFSYSLOGS'] = "1ページに表示する Syslog メッセージ数";
$content['LN_INSTALL_MSGCHARLIMIT'] = "メインビューで表示するメッセージの文字数";
$content['LN_INSTALL_STRCHARLIMIT'] = "文字列型フィールドを文字表示制限";
$content['LN_INSTALL_SHOWDETAILPOP'] = "ポップアップを使用し、詳細なメッセージを表示する";
$content['LN_INSTALL_AUTORESOLVIP'] = "自動的にIPアドレスの逆引きを行う(インライン)";
$content['LN_INSTALL_USERDBOPTIONS'] = "ユーザデータベース オプション";
$content['LN_INSTALL_ENABLEUSERDB'] = "ユーザデータベースを有効にする";
$content['LN_INSTALL_SUCCESSSTATEMENTS'] = "Successfully executed statements:";
$content['LN_INSTALL_FAILEDSTATEMENTS'] = "Failed statements:";
$content['LN_INSTALL_STEP5_TEXT_NEXT'] = "You can now proceed to the <B>next</B> step adding the first LogAnalyzer Admin User!";
$content['LN_INSTALL_STEP5_TEXT_FAILED'] = "At least one statement failed,see error reasons below";
$content['LN_INSTALL_ERRORMSG'] = "エラーメッセージ";
$content['LN_INSTALL_SQLSTATEMENT'] = "SQL Statement";
$content['LN_INSTALL_CREATEUSER'] = "ユーザアカウントの作成";
$content['LN_INSTALL_PASSWORD'] = "パスワード";
$content['LN_INSTALL_PASSWORDREPEAT'] = "パスワード(確認)";
$content['LN_INSTALL_SUCCESSCREATED'] = "ユーザアカウントの作成が完了しました。";
$content['LN_INSTALL_RECHECK'] = "ReCheck";
$content['LN_INSTALL_FINISH'] = "Finish!";
$content['LN_INSTALL_LDAPCONNECTFAILED'] = "LDAPサーバ '%1' に接続出来ませんでした。";
$content['LN_INSTALL_WARNINGMYSQL'] = "A MYSQL database Server is required for this feature. Other database engines are not supported for the User Database System. However for logsources, there is support for other database systems.";
$content['LN_INSTALL_'] = "";

// Converter Site
$content['LN_CONVERT_TITLE'] = "Configuration Converter Step %1";
$content['LN_CONVERT_NOTALLOWED'] = "Login";
$content['LN_CONVERT_ERRORINSTALLED'] = 'LogAnalyzer is not allowed to convert your settings into the user database.<br><br> If you want to convert your convert your settings, add the variable following into your config.php: <br><b>$CFG[\'UserDBConvertAllowed\'] = true;</b><br><br> Click <A HREF="index.php">here</A> to return to pgpLogCon start page.';
$content['LN_CONVERT_STEP1'] = "Step 1 - Informations";
$content['LN_CONVERT_STEP2'] = "Step 2 - Create Tables";
$content['LN_CONVERT_STEP3'] = "Step 3 - Check SQL Results";
$content['LN_CONVERT_STEP4'] = "Step 4 - Creating the Main Useraccount";
$content['LN_CONVERT_STEP5'] = "Step 5 - Import Settings into UserDB";
$content['LN_CONVERT_TITLETOP'] = "Converting LogAnalyzer configuration settings - Step ";
$content['LN_CONVERT_STEP1_TEXT'] = 'This script allows you to import your existing configuration from the <b>config.php</b> file. This includes frontend settings, data sources, custom views and custom searches. Do only perform this conversion if you did install LogAnalyzer without the UserDB System, and decided to enable it now. <br><br><b>ANY EXISTING INSTANCE OF A USERDB WILL BE OVERWRITTEN!</b><br><br><input type="submit" value="Click here"> to start the first conversion step!';
$content['LN_CONVERT_STEP2_TEXT'] = 'The database connection has been successfully verified! <br><br>The next step will be to create the necessary database tables for the LogAnalyzer User System. This might take a while! <br><b>WARNING</b>, if you have an existing LogAnalyzer installation in this database with the same tableprefix, all your data will be <b>OVERWRITTEN</b>!<br> Make sure you are using a fresh database, or you want to overwrite your old LogAnalyzer database.<br><br><b>Click on <input type="submit" value="Next"> to start the creation of the tables</b>';
$content['LN_CONVERT_STEP5_TEXT'] = '<input type="submit" value="Click here"> to start the last step of the conversion. In this step, your existing configuration from the <b>config.php</b> will be imported into the database.';
$content['LN_CONVERT_STEP6'] = "Step 8 - Done";
$content['LN_CONVERT_STEP6_TEXT'] = 'Congratulations! You have successfully converted your existing LogAnalyzer installation :)!<br><br>Important! Don\'t forget to REMOVE THE VARIABLES <b>$CFG[\'UserDBConvertAllowed\'] = true;</b> from your config.php file! <br><br>You can click <a href="index.php">here</a> to get to your LogAnalyzerinstallation.';
$content['LN_CONVERT_PROCESS'] = "Conversion Progress:";
$content['LN_CONVERT_ERROR_SOURCEIMPORT'] = "Critical Error while importing the sources into the database, the SourceType '%1' is not supported by this LogAnalyzer Version.";

// Stats Site
	$content['LN_STATS_CHARTTITLE'] = "Top %1 '%2' sorted by messagecount";
	$content['LN_STATS_COUNTBY'] = "Messagecount by '%1'";
	$content['LN_STATS_OTHERS'] = "All Others";
	$content['LN_STATS_TOPRECORDS'] = "Maxrecords: %1";
	$content['LN_STATS_GENERATEDAT'] = "Generated at: %1";
//	$content['LN_STATS_COUNTBYSYSLOGTAG'] = "Messagecount by SyslogTag";
	$content['LN_STATS_GRAPH'] = "Graph";
	$content['LN_GEN_ERROR_INVALIDFIELD'] = "Invalid fieldname";
	$content['LN_GEN_ERROR_MISSINGCHARTFIELD'] = "Missing fieldname";
	$content['LN_GEN_ERROR_INVALIDTYPE'] = "Invalid or unknown chart type.";
	$content['LN_ERROR_CHARTS_NOTCONFIGURED'] = "There are no charts configured at all.";
	$content['LN_CHART_TYPE'] = "チャート種別";
	$content['LN_CHART_WIDTH'] = "Chart width";
	$content['LN_CHART_FIELD'] = "Chart field";
	$content['LN_CHART_MAXRECORDS'] = "Top records count";
	$content['LN_CHART_SHOWPERCENT'] = "Show percentage data";
	$content['LN_CHART_TYPE_CAKE'] = "Cake (Pie)";
	$content['LN_CHART_TYPE_BARS_VERTICAL'] = "Bars vertical";
	$content['LN_CHART_TYPE_BARS_HORIZONTAL'] = "Bars horizontal";
	$content['LN_STATS_WARNINGDISPLAY'] = "Generating graphics on large data sources currently is very time consuming. This will be addressed in later versions. If processing takes too long, please simply cancel the request.";

// asktheoracle site
$content['LN_ORACLE_TITLE'] = "Asking the oracle for '%1'";
$content['LN_ORACLE_HELP_FOR'] = "These are the links the oracle got for you";
$content['LN_ORACLE_HELP_TEXT'] = "<br><h3>You asked the oracle to find more information about the '%1' value '%2'.</h3>
<p align=\"left\">This pages enables you do a a search over multiple log sources. %3
<br>The overall idea is to make it easy to find information about a specific subject in all places where it may exist.
</p>
<p align=\"left\">A useful use case may be a hack attempt you see in a web log. Click on the attacker's IP, which brings up this search page here. Now you can both lookup information about the IP range as well as check your other logs (e.g. firewall or mail) if they contain information about the attacker. We hope that this facilitates your analysis process.
</p>
";
$content['LN_ORACLE_HELP_TEXT_EXTERNAL'] = "It also enables you to perform canned searches over some external databases";
$content['LN_ORACLE_HELP_DETAIL'] = "Link matrix for the '%1' value '%2'";
$content['LN_ORACLE_SEARCH'] = "Search"; // in '%1' Field";
$content['LN_ORACLE_SOURCENAME'] = "Source name";
$content['LN_ORACLE_FIELD'] = "Field";
$content['LN_ORACLE_ONLINESEARCH'] = "Online Search";
$content['LN_ORACLE_WHOIS'] = "WHOIS Lookup for '%1' value '%2'";

// Report Strings
$content['LN_GEN_ERROR_INVALIDOP'] = "Invalid or missing operation type";
$content['LN_GEN_ERROR_INVALIDREPORTID'] = "Invalid or missing report id";
$content['LN_GEN_ERROR_MISSINGSAVEDREPORTID'] = "Invalid or missing savedreport id";
$content['LN_GEN_ERROR_REPORTGENFAILED'] = "Failed generating report '%1' with the following error reason: %2";
$content['LN_GEN_ERROR_WHILEREPORTGEN'] = "Error occured while generating report"; 
$content['LN_GEN_ERROR_REPORT_NODATA'] = "No data found for report generation"; 
$content['LN_GEN_ALL_OTHER_EVENTS'] = "All other events";
$content['LN_REPORT_FOOTER_ENDERED'] = "Report rendered in";
$content['LN_REPORT_FILTERS'] = "List of used filters";
$content['LN_REPORT_FILTERTYPE_DATE'] = "Date";
$content['LN_REPORT_FILTERTYPE_NUMBER'] = "Number";
$content['LN_REPORT_FILTERTYPE_STRING'] = "String";
$content['LN_GEN_SUCCESS_WHILEREPORTGEN'] = "Report was successfully generated";
$content['LN_GEN_ERROR_REPORTFAILEDTOGENERATE'] = "Failed to generate report, error details: %1";
$content['LN_GEN_SUCCESS_REPORTWASGENERATED_DETAILS'] = "Successfully generated report: %1";
$content['LN_ERROR_PATH_NOT_ALLOWED'] = "The file is not located in the allowed directories list (By default /var/log is allowed only)."; 
$content['LN_ERROR_PATH_NOT_ALLOWED_EXTRA'] = "The file '%1' is not located in one of these directories: '%2'"; 

$content['LN_CMD_RUNREPORT'] = "Generating saved report '%1'";
$content['LN_CMD_REPORTIDNOTFOUND'] = "Invalid Report ID '%1'";
$content['LN_CMD_SAVEDREPORTIDNOTFOUND'] = "Invalid SavedReport ID '%1'";
$content['LN_CMD_NOREPORTID'] = "Missing Report ID";
$content['LN_CMD_NOSAVEDREPORTID'] = "Missing SavedReport ID";
$content['LN_CMD_NOCMDPROMPT'] = "Error, this script can only be run from the command prompt.";
$content['LN_REPORT_GENERATEDTIME'] = "Report generated at: ";

$content['LN_REPORT_ACTIONS'] = "Run Report Actions";
$content['LN_REPORTS_CAT'] = "Report Category";
$content['LN_REPORTS_ID'] = "Report ID";
$content['LN_REPORTS_NAME'] = "Report Name";
$content['LN_REPORTS_DESCRIPTION'] = "Report Description";
$content['LN_REPORTS_HELP'] = "Help";
$content['LN_REPORTS_HELP_CLICK'] = "Click here for a detailed report description";
$content['LN_REPORTS_INFO'] = "Show more Information";
$content['LN_REPORTS_SAVEDREPORTS'] = "Saved reports";
$content['LN_REPORTS_ADMIN'] = "Administrate Reports";
$content['LN_REPORTMENU_LIST'] = "List installed Reports";
$content['LN_REPORTMENU_ONLINELIST'] = "All Available Reports";
$content['LN_REPORTS_INFORMATION'] = "This page shows a list of installed and available reports including saved report configurations.
<br/>To run a report, click on the buttons right to the Saved Reports.
<br/>Attention! Generating reports can be very time consuming depending on the size of your database.
";
$content['LN_REPORTS_CHECKLOGSTREAMSOURCE'] = "Verify Logstream optimization";
$content['LN_REPORTS_RUNNOW'] = "Run saved report now!";
$content['LN_REPORTS_ERROR_ERRORCHECKINGSOURCE'] = "Error while checking Savedreport Source: %1";

?>
