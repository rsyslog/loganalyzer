/*
Javscript menu
IE & Others Stuff
*/

inDragMode=0;
_d.onmousemove=getMouseXY;
_flta="return false";
if(ie55)_flta="try{if(ap.filters){return 1}}catch(e){}";
_d.write("<"+"script>function getflta(ap){"+_flta+"}<"+"/script>");
_mot=0;
gevent=0;
_ifc=0;


function $CtI($ti)
{
	clearTimeout($ti)
}

function getMouseXY(e)
{
	if(ns6)
	{
		MouseX=e.pageX;
		MouseY=e.pageY
	}
	else
	{
		MouseX=event.clientX;
		MouseY=event.clientY
	}
	if(!op&&_d.all&&_d.body)
	{
		MouseX=MouseX+_d.body.scrollLeft;
		MouseY=MouseY+_d.body.scrollTop;
		
		if(IEDtD&&!mac)
		{
			MouseY=MouseY+_sT;
			MouseX=MouseX+_sL;
		}
	}
	if(inDragMode)
	{
		gm=gmobj(DragLayer);
		spos(gm,MouseY-DragY,MouseX-DragX);
		return false
	}
	return _t;
}

function gmobj(_mtxt)
{
	if(_d.getElementById){return _d.getElementById(_mtxt)}else if(_d.all){return _d.all[_mtxt]}
}

function spos(_gm,_t,_l,_h,_w)
{
	_px="px";
	if(op){_px="";
	_gs=_gm.style;
	if(_w!=_n)_gs.pixelWidth=_w;
	if(_h!=_n)_gs.pixelHeight=_h}else{_gs=_gm.style;
	if(_w!=_n)_gs.width=_w+_px;
	if(_h!=_n)_gs.height=_h+_px}if(_t!=_n)_gs.top=_t+_px;
	if(_l!=_n)_gs.left=_l+_px}function gpos(_gm){_h=_gm.offsetHeight;
	_w=_gm.offsetWidth;
	if(op5){_h=_gm.style.pixelHeight;
	_w=_gm.style.pixelWidth}_tgm=_gm;
	_t=0;
	while(_tgm!=_n){_t+=_tgm.offsetTop;
	_tgm=_tgm.offsetParent}_tgm=_gm;
	_l=0;
	while(_tgm!=_n){_l+=_tgm.offsetLeft;
	_tgm=_tgm.offsetParent}
	
	if(sfri)
	{	_l-=_d.body.offsetLeft;
		_t-=_d.body.offsetTop
	}
	if(mac&&!mac45){if(_macffs=_d.body.currentStyle.marginTop){_t=_t+parseInt(_macffs)}if(_macffs=_d.body.currentStyle.marginLeft){_l=_l+parseInt(_macffs)}}_gpa=new Array(_t,_l,_h,_w);
	return(_gpa)
}

function applyFilter(_gm,_mnu)
{
	if(getflta(_gm)){if(_gm.style.visibility=="visible")flt=_m[_mnu][16];
	else flt=_m[_mnu][15];
	if(flt){if(_gm.filters[0])_gm.filters[0].stop();
	iedf="";
	iedf="FILTER:";
	flt=flt.split(";");
	for(fx=0;
	fx<flt.length;
	fx++){iedf+=" progid:DXImageTransform.Microsoft."+flt[fx];
	if(navigator.appVersion.indexOf("MSIE 5.5")>0)fx=999}_gm.style.filter=iedf;
	_gm.filters[0].apply()}}}function playFilter(_gm,_mnu){if(getflta(_gm)){if(_gm.style.visibility=="visible")flt=_m[_mnu][15];
	else flt=_m[_mnu][16];
	if(flt)_gm.filters[0].play()}
}

function menuDisplay(_mnu,_show)
{
	_gmD=gmobj("menu"+_mnu);
	if(!_gmD)return;
	_m[_mnu][22]=_gmD;
	M_hideLayer(_mnu,_show);
	if(_show){if(_m[_mnu][21]>-1&&_m[_mnu][21]!=_itemRef){itemOff(_m[_mnu][21]);
	_m[_mnu][21]=_itemRef}if(_m[_mnu][7]==0&&_ofMT==1)return;
	if(_gmD.style.visibility.toUpperCase()!="VISIBLE"){_SoT(_mnu,1);
	applyFilter(_gmD,_mnu);
	if(!_m[_mnu][7]&&!_m[_mnu][14]&&ns6)_gmD.style.position="fixed";
	_gmD.style.zIndex=_zi;
	_gmD.style.visibility="visible";
	playFilter(_gmD,_mnu);
	if(!_m[_mnu][7])_m[_mnu][21]=_itemRef;
	_mnuD++}}else{if(_m[_mnu][21]>-1&&_itemRef!=_m[_mnu][21])itemOff(_m[_mnu][21]);
	if(_gmD.style.visibility.toUpperCase()=="VISIBLE"){_SoT(_mnu,0);
	applyFilter(_gmD,_mnu);
	if(!_m[_mnu][14]&&ns6)_gmD.style.position="absolute";
	_gmD.style.visibility="hidden";
	if(mac||konq){_gmD.style.top="-999px";
	_gmD.style.left="-999px"}playFilter(_gmD,_mnu);
	_mnuD--}_m[_mnu][21]=-1}
}

function closeAllMenus()
{
	if(_oldel>-1)itemOff(_oldel);
	_oldel=-1;
	for(_a=0;
	_a<_m.length;
	_a++){if(!_m[_a][7]&&!_m[_a][10])menuDisplay(_a,0)}_mnuD=0;
	_zi=999;
	_itemRef=-1}_lcC=0;
	function _lc(_i){_I=_mi[_i];
	_lcC++;
	if(_I[62]&&_lcC==1)eval(_I[62]);
	if(_I[34]=="disabled")return;
	_feat="";
	if(_I[57])_feat=_I[57];
	if(op||_feat||(sfri||ns6||konq||mac45)){_trg="";
	if(_I[35])_trg=_I[35];
	if(_trg)window.open(_I[2],_trg,_feat);
	else(location.href=_I[2])}else{_gm=gmobj("lnk"+_i);
	_gm.href=_I[2];
	_gm.click()}closeAllMenus();
	if(_lcC==2)_lcC=0;
}

function getMenuByItem(_gel)
{
	_gel=_mi[_gel][0];
	if(_m[_gel][7])_gel=-1;
	return _gel;
}

function getParentMenuByItem(_gel)
{
	_tm=getMenuByItem(_gel);
	if(_tm==-1)return-1;
	for(_x=0;
	_x<_mi.length;
	_x++){if(_mi[_x][3]==_m[_tm][1]){return _mi[_x][0]}}
}

function getParentItemByItem(_gel)
{
	_tm=getMenuByItem(_gel);
	if(_tm==-1)return-1;
	for(_x=0;
	_x<_mi.length;
	_x++){if(_mi[_x][3]==_m[_tm][1]){return _x;}}
}

function getMenuByName(_mn)
{
	_mn=$tL(_mn);
	for(_xg=0;_xg<_m.length;_xg++)
	{
		if(_mn==_m[_xg][1])
			return _xg;
	}
}

function itemOn(_i)
{
	$CtI(_mot);
	_mot=null;
	_gmi=gmobj("el"+_i);
	if(_gmi.itemOn==1)return;
	_gmi.itemOn=1;
	_gmt=gmobj("tr"+_i);
	var _I=_mi[_i];
	if(_I[34]=="header")return;
	if(_gmt){_gmt=gmobj("tr"+_i);
	_gs=_gmt.style;
	if(_I[53])_gmt.className=_I[53]}else{_gs=_gmi.style}if(_I[2]||_I[3]){_mP=(ns6)?"pointer":"hand";
	if(_I[59])_mP=_I[59];
	_gs.cursor=_mP;
	if(_I[29])gmobj("img"+_i).style.cursor=_gs.cursor;
	if(_I[24]&&_I[3])gmobj("simg"+_i).style.cursor=_gs.cursor}if(_I[32]&&_I[29]){gmobj("img"+_i).src=_I[32]}if(_I[3]&&_I[24]&&_I[48]){gmobj("simg"+_i).src=_I[48]}if(_I[53])_gmi.className=_I[53];
	if(_I[6])_gs.color=_I[6];
	if(_I[5])_gmi.style.background=_I[5];
	if(_I[47]){_oi="url("+_I[47]+")";
	if(_gmi.style.backgroundImage!=_oi);
	_gmi.style.backgroundImage=_oi}if(_I[26])_gs.textDecoration=_I[26];
	if(!mac){if(_I[44])_gs.fontWeight="bold";
	if(_I[45])_gs.fontStyle="italic"}if(_I[42])eval(_I[42]);
	if(_I[25]){_gmi.style.border=_I[25];
	if(!_I[9])_gs.padding=_I[11]-parseInt(_gmi.style.borderWidth)+"px";}
}

function itemOff(_i)
{
	_gmi=gmobj("el"+_i);
	if(_gmi.itemOn==0)return;
	_gmi.itemOn=0;
	_gmt=gmobj("tr"+_i);
	var _I=_mi[_i];
	if(_I[32]&&_I[29]){gmobj("img"+_i).src=_I[29]}if(_I[3]&&_I[24]&&_I[48]){gmobj("simg"+_i).src=_I[24]}if(_I[4]&&_I[4]!="none")window.status="";
	if(_i==-1)return;
	if(_gmt){_gmt=gmobj("tr"+_i);
	_gs=_gmt.style;
	if(_I[54])_gmt.className=_I[54]}else{_gs=_gmi.style}if(_I[54])_gmi.className=_I[54];
	if(_I[46])_gmi.style.backgroundImage="url("+_I[46]+")";
	else if(_I[7])_gmi.style.background=_I[7];
	if(_I[8])_gs.color=_I[8];
	if(_I[26])_gs.textDecoration="none";
	if(_I[33])_gs.textDecoration=_I[33];
	if(!mac){if(_I[44]&&(_I[14]=="normal"||!_I[14]))_gs.fontWeight="normal";
	if(_I[45]&&(_I[13]=="normal"||!_I[13]))_gs.fontStyle="normal"}if(!_startM&&_I[43])eval(_I[43]);
	if(_I[25]){_gmi.style.border="0px";
	if(!_I[9])_gs.padding=_I[11]+"px"}if(_I[9]){_gmi.style.border=_I[9];}
}

function closeMenusByArray(_cmnu)
{
	for(_a=0;
	_a<_cmnu.length;
	_a++)if(_cmnu[_a]!=_mnu)if(!_m[_cmnu[_a]][7])menuDisplay(_cmnu[_a],0);
}

function getMenusToClose()
{
	_st=-1;
	_en=_sm.length;
	_mm=_iP;
	if(_iP==-1){if(_sm[0]!=_masterMenu)return _sm;
	_mm=_masterMenu}for(_b=0;
	_b<_sm.length;
	_b++){if(_sm[_b]==_mm)_st=_b+1;
	if(_sm[_b]==_mnu)_en=_b}if(_st>-1&&_en>-1){_tsm=_sm.slice(_st,_en)}return _tsm}function _cm(){_tar=getMenusToClose();
	closeMenusByArray(_tar);
	for(_b=0;
	_b<_tar.length;
	_b++){if(_tar[_b]!=_mnu)_sm=remove(_sm,_tar[_b]);}
}

function _getDims()
{
	if(!op&&_d.all){_mc=_d.body;
	if(IEDtD&&!mac&&!op7)_mc=_d.documentElement;
	if(!_mc)return;
	_bH=_mc.clientHeight;
	_bW=_mc.clientWidth;
	_sT=_mc.scrollTop;
	_sL=_mc.scrollLeft}else{_bH=window.innerHeight;
	_bW=window.innerWidth;
	if(ns6){if(_d.documentElement.offsetWidth!=_bW){_bW=_bW-15}}_sT=self.scrollY;
	_sL=self.scrollX;
	if(op){_sT=_d.body.scrollTop;
	_sL=_d.body.scrollleft}}}function c_openMenu(_i){var _I=_mi[_i];
	if(_I[3]){_oldMC=_I[39];
	_I[39]=0;
	_oldMD=_menuOpenDelay;
	_menuOpenDelay=0;
	_gm=gmobj("menu"+getMenuByName(_I[3]));
	if(_gm.style.visibility=="visible"&&_I[40]){menuDisplay(getMenuByName(_I[3]),0);
	itemOn(_i)}else{_popi(_i)}_menuOpenDelay=_oldMD;
	_I[39]=_oldMC}else{if(_I[2]&&_I[39])eval(_I[2]);}
}

function getOffsetValue(_ofs)
{
	_ofsv=null;
	if(_ofs)
		{_ofsv=_ofs;}
	if(isNaN(_ofs)&&_ofs.indexOf("offset=")==0)
		{_ofsv=parseInt(_ofs.substr(7,99));}
	return _ofsv;
}

function popup()
	{
	_sm=new Array;
	_arg=arguments;
	$CtI(_MT);
	_MT=null;
	$CtI(_oMT);
	_oMT=null;
	closeAllMenus();
	if(_arg[0]){_ofMT=0;
	_mnu=getMenuByName(_arg[0]);
	if(ie4)_fixMenu(_mnu);
	_tos=0;
	if(_arg[2])_tos=_arg[2];
	_los=0;
	if(_arg[3])_los=_arg[3];
	_sm[_sm.length]=_mnu;
	if(ns6&&!ns60){_los-=_sL;
	_tos-=_sT;
	_gm=gmobj("menu"+_mnu);
	_gp=gpos(_gm);
	spos(_gm,_m[_mnu][2]+_tos,_m[_mnu][3]+_los)}if(mac)spos(gmobj("menu"+_mnu),_m[_mnu][2],_m[_mnu][3]);
	if(_arg[1]){_gm=gmobj("menu"+_mnu);
	if(!_gm)return;
	_gp=gpos(_gm);
	if(_arg[1]==1){if(MouseY+_gp[2]>(_bH+_sT))_tos=-(MouseY+_gp[2]-_bH)+_sT;
	if(MouseX+_gp[3]>(_bW+_sL))_los=-(MouseX+_gp[3]-_bW)+_sL;
	if(_m[_mnu][2]){if(isNaN(_m[_mnu][2]))_tos=getOffsetValue(_m[_mnu][2]);
	else{_tos=_m[_mnu][2];
	MouseY=0}}if(_m[_mnu][3]){if(isNaN(_m[_mnu][3]))_los=getOffsetValue(_m[_mnu][3]);
	else{_los=_m[_mnu][3];
	MouseX=0}}spos(_gm,(MouseY+_tos),(MouseX+_los))}else{_po=gmobj(_arg[1]);
	_pp=gpos(_po);
	spos(_gm,_pp[0]+_pp[2]+getOffsetValue(_m[_mnu][2])+_tos,_pp[1]+getOffsetValue(_m[_mnu][3])+_los)}}_zi=_zi+100;
	if(_m[_mnu][13]=="scroll")_check4Scroll(_mnu);
	menuDisplay(_mnu,1);
	_m[_mnu][21]=-1;}
}

function popdown()
{
	_MT=setTimeout("closeAllMenus()",_menuCloseDelay)
}
	
function _popi(_i)
{
	var _I=_mi[_i];
	if(!_I)return;
	_pMnu=_m[_I[0]];
	$CtI(_MT);
	_MT=null;
	if(_oldel>-1){gm=0;
	if(_I[3]){gm=gmobj("menu"+getMenuByName(_I[3]));
	if(gm&&gm.style.visibility.toUpperCase()=="VISIBLE"&&_i==_oldel){itemOn(_i);
	return}}if(_oldel!=_i)itemOff(_oldel);
	$CtI(_oMT);
	_oMT=null}$CtI(_cMT);
	_cMT=null;
	_mnu=-1;
	_itemRef=_i;
	if(_I[34]=="disabled")return;
	_mopen=_I[3];
	horiz=0;
	if(_pMnu[9])horiz=1;
	itemOn(_i);
	if(!_sm.length){_sm[_sm.length]=_I[0];
	_masterMenu=_I[0]}_iP=getMenuByItem(_i);
	if(_iP==-1)_masterMenu=_I[0];
	if(_I[4]!="none"){if(_I[4]!=null)window.status=_I[4];
	else if(_I[2])window.status=_I[2]}_cMT=setTimeout("_cm()",_menuOpenDelay);
	if(_I[39]){if(_mopen){_mnu=getMenuByName(_mopen);
	_gm=gmobj("menu"+_mnu);
	if(_gm.style.visibility.toUpperCase()=="VISIBLE"){$CtI(_cMT);
	_cMT=null;
	_tsm=_sm[_sm.length-1];
	if(_tsm!=_mnu)menuDisplay(_tsm,0)}}}if(!window.retainClickValue)inopenmode=0;
	if(_mopen&&(!_I[39]||inopenmode)&&_I[34]!="tree"){_getDims();
	_pm=gmobj("menu"+_I[0]);
	_pp=gpos(_pm);
	_mnu=getMenuByName(_mopen);
	if(_I[41])_m[_mnu][10]=1;
	if(ie4||op||konq)_fixMenu(_mnu);
	if(_mnu>-1){if(_oldel>-1&&(_mi[_oldel][0]+_I[0]))menuDisplay(_mnu,0);
	_oMT=setTimeout("menuDisplay("+_mnu+",1)",_menuOpenDelay);
	_mnO=gmobj("menu"+_mnu);
	_mp=gpos(_mnO);
	if(ie4){_mnT=gmobj("tbl"+_mnu);
	_tp=gpos(_mnT);
	_mp[3]=_tp[3]}_gmi=gmobj("el"+_i);
	if(!horiz&&mac)_gmi=gmobj("pTR"+_i);
	_gp=gpos(_gmi);
	if(horiz){_left=_gp[1];
	_top=_pp[0]+_pp[2]-_I[65]}else{_left=_pp[1]+_pp[3]-_I[65];
	_top=_gp[0]}if(sfri){if(_pMnu[14]=="relative"){_left=_left+_d.body.offsetLeft;
	_top=_top+_d.body.offsetTop}}if(_pMnu[13]=="scroll"&&!op&&!mac45&&!sfri&&!konq){if(ns6&&!ns7)_top=_top-gevent;
	else _top=_top-_pm.scrollTop}if(_m[_mnu][2]!=_n){if(isNaN(_m[_mnu][2])&&_m[_mnu][2].indexOf("offset=")==0){_top=_top+getOffsetValue(_m[_mnu][2])}else{_top=_m[_mnu][2]}}if(_m[_mnu][3]!=_n){if(isNaN(_m[_mnu][3])&&_m[_mnu][3].indexOf("offset=")==0){_left=_left+getOffsetValue(_m[_mnu][3])}else{_left=_m[_mnu][3]}}if(!horiz&&(_top+_mp[2]+20)>(_bH+_sT)){_top=(_bH-_mp[2])+_sT-16}if(_left+_mp[3]>_bW+_sL){if(!horiz&&(_pp[1]-_mp[3])>0){_left=_pp[1]-_mp[3]-_subOffsetLeft+_pMnu[6][65]}else{_left=(_bW-_mp[3])-8}}if(horiz){if(_m[_mnu][11]=="rtl")_left=_left-_mp[3]+_gp[3]+2;
	if(_pMnu[5]&&_pMnu[5].indexOf("bottom")!=-1){_top=_pp[0]-_mp[2]-1}}else{if(_m[_mnu][11]=="rtl")_left=_pp[1]-_mp[3]-(_subOffsetLeft*2);
	_top+=_subOffsetTop;
	_left+=_subOffsetLeft}if(_left<2)_left=2;
	if(_top<2)_top=2;
	if(ns60){_left-=+_pMnu[6][65];
	_top-=+_pMnu[6][65]}if(mac){_left-=_pMnu[12]+_pMnu[6][65];
	_top-=_pMnu[12]+_pMnu[6][65]}if(sfri||op){if(!horiz){_top-=_pMnu[6][65]}else{_left-=_pMnu[6][65]}}if(_m[_I[0]][7]&&(ns6||ns7))_top=_top-_sT;
	spos(_mnO,_top,_left);
	if(_m[_mnu][5])_setPosition(_mnu);
	if(_m[_mnu][13]=="scroll")_check4Scroll(_mnu);
	_zi++;
	_mnO.style.zIndex=_zi;
	if(_sm[_sm.length-1]!=_mnu)_sm[_sm.length]=_mnu}}_setPath(_iP);
	_oldel=_i;
	_ofMT=0}function _check4Scroll(_mnu){if(op)return;
	_M=_m[_mnu];
	gm=gmobj("menu"+_mnu);
	_gp=gpos(gm);
	gmt=gmobj("tbl"+_mnu);
	_gt=gpos(gmt);
	_MS=_mi[_M[0][0]];
	_cor=(_M[12]*2)+(_MS[65]*2);
	_sdim=_gt[2]+_sT;
	if(horiz)_sdim=_gt[2]+_gt[0]-_sT;
	if(_m[_mnu][2]&&!isNaN(_m[_mnu][2]))_sdim=_m[_mnu][2]+_gt[2];
	if(_sdim<(_bH+_sT)){gm.style.overflow="";
	_top=_n;
	if(!horiz&&(_gt[0]+_gt[2]+16)>(_bH+_sT)){_top=(_bH-_gt[2])+_sT-16}_ofx=0;
	if(op7)_ofx=_cor;
	_ofy=0;
	if(mac)_ofy=_cor;
	spos(gm,_top,_n,_gt[2]+_ofy,_gt[3]+_ofx);
	return}gm.style.overflow="auto";
	_sbw=_gt[3];
	if(mac){if(IEDtD)_sbw=_sbw+16;
	else _sbw=_sbw+16+_cor;
	_btm=gmobj("btm"+_mnu);
	_btm.style.height=_M[12]*2+"px"}else if(IEDtD){if(op7){_sbw=_sbw+16}else{_sbw+=_d.documentElement.offsetWidth-_d.documentElement.clientWidth-3}}else{if(op7){_sbw=_sbw+16+_cor}else{_sbw+=_d.body.offsetWidth-_d.body.clientWidth-4+_cor}if(ie4)_sbw=_gt[3]+16+_cor;
	if(ns6||sfri){_sbw=_gt[3]+15;
	if(!navigator.vendor)_sbw=_sbw+4}}_top=_n;
	if(horiz){_ht=_bH-_gt[0]-16+_sT}else{_ht=_bH-16;
	_top=6+_sT}_left=_n;
	if(_gp[1]+_sbw>(_bW+_sL)){_left=(_bW-_sbw)-2}if(_m[_mnu][2]&&!isNaN(_m[_mnu][2])){_top=_m[_mnu][2];
	_ht=_bH-_top-6}if(_ht>0)spos(gm,_top,_left,_ht+2,_sbw)}function _setPath(_mpi){if(_mpi>-1){_ci=_m[_mpi][21];
	while(_ci>-1){itemOn(_ci);
	_ci=_m[_mi[_ci][0]][21]}}}function _CAMs(){_MT=setTimeout("_AClose()",_menuCloseDelay);
	$CtI(_oMT);
	_oMT=null;
	_ofMT=1;
}

function _AClose()
{
	if(_ofMT==1)
	{
		closeAllMenus(); 
		inopenmode=0;
	}
}

function _setCPage(_i)
{
	if(_i[18])_i[8]=_i[18];
	if(_i[19])_i[7]=_i[19];
	if(_i[56]&&_i[29])_i[29]=_i[56];
	if(_i[69])_i[46]=_i[69];
	if(_i[48]&&_i[3])_i[24]=_i[48];
	if(_i[25])_i[9]=_i[25];
	if(_i[72])_i[54]=_i[72]
}

function _getCurrentPage()
{
	_I=_mi[_el];
	if(_I[2]){_url=_I[2];
	_hrf=location.href;
	fstr=_hrf.substr((_hrf.length-_url.length),_url.length);
	if(fstr==_url){_setCPage(_I);
	_cip[_cip.length]=_el;}}
}

function _oifx(_i)
{
	_G=gmobj("simg"+_i);
	spos(_G,_n,_n,_G.height,_G.width);
	spos(gmobj("el"+_i),_n,_n,_G.height,_G.width);
}

function _getLink(_I,_gli)
{
	_link="";
	actiontext+=" onMouseOver=\"_popi("+_gli+")\" onclick=\"opentree();";
	if(_I[2]){_targ="";
	if(_I[35])_targ="target="+_I[35];
	_link="<a id=lnk"+_gli+" href=\""+_I[2]+"\" "+_targ+"></a>";
	actiontext+="_lc("+_gli+");c_openMenu("+_gli+")"}actiontext+="\"";
	return _link;
}

function drawItem(_i)
{
	_I=_mi[_el];
	_mnu=_I[0];
	var _M=_m[_mnu];
	_getCurrentPage();
	if(_I[34]=="header")
	{
		if(_I[20])
			_I[8]=_I[20];
		if(_I[21])
			_I[7]=_I[21];
	}
	_ofb=(_I[46]?"background-image:url("+_I[46]+");":"");
	if(!_ofb)
		_ofb=(_I[7]?"background:"+_I[7]:"");

	_ofc=(_I[8]?"color:"+_I[8]:"");
	_fsize=(_I[12]?";font-Size:"+_I[12]:"");
	_fstyle=(_I[13]?";font-Style:"+_I[13]:"");
	_fweight=(_I[14]?";font-Weight:"+_I[14]:"");
	_ffam=(_I[15]?";font-Family:"+_I[15]:"");
	_tdec="";

	if(_I[33])
		_tdec=";text-Decoration:"+_I[33];
	actiontext=" onmouseout=_mot=setTimeout(\"itemOff("+_el+")\",100) ";
	_link="";
	if(_I[39])
	{
		actiontext+=" onclick=\"inopenmode=1;c_openMenu("+_el+");\" onMouseOver=\"_popi("+_el+");\"";
	}
	else
	{
		_link=_getLink(_I,_el);
	}
	if(_I[34]=="dragable")
		actiontext+=" onmousedown=\"drag_drop('menu"+_mnu+"')\"";
		
	_clss="";
	if(_I[54])
		_clss="class="+_I[54];
	if(horiz)
	{
		if(_i==0)
			_mt+="<tr "+_clss+">";
	}
	else
		_mt+="<tr id=pTR"+_el+" "+_clss+">";

	_subC=0;
	if(_I[3]&&_I[24])
		_subC=1;
	_timg="";
	_bimg="";
	if(_I[29])
	{
		_imalgn="";
		if(_I[31])
			_imalgn="align="+_I[31];
		_imcspan="";
		if(_subC&&_imalgn&&_I[31]!="left")
			_imcspan="colspan=2";
		_imgwd="width=1";
		if(_imalgn&&_I[31]!="left")
			_imgwd="";
		_Iwid="";
		if(_I[37])
			_Iwid=" width="+_I[37];
		_Ihgt="";
		if(_I[38])
			_Ihgt=" height="+_I[38];
		_imgalt="";
		if(_I[58])
			_imgalt="alt=\""+_I[58]+"\"";
		_timg="<td "+_imcspan+" "+_imalgn+" "+_imgwd+"><img "+_imgalt+" "+_Iwid+_Ihgt+" id=img"+_el+" src=\""+_I[29]+"\"></td>";
		if(_I[30]=="top")
			_timg+="</tr><tr>";
		if(_I[30]=="right")
		{
			_bimg=_timg;
			_timg="";
		}
		if(_I[30]=="bottom")
		{
			_bimg="<tr>"+_timg+"</tr>";
			_timg="";
		}
	}
	_algn="";
	if(_M[8])
		_algn="align="+_M[8];
	if(_I[36])
		_algn="align="+_I[36];
	if(_M[8])
		_algn=" valign="+_M[8];
	if(_I[61])
		_algn=" valign="+_I[61];
	_iw="";
	_iheight="";
	_padd="padding:"+_I[11]+"px";
	_offbrd="";
	if(_I[9])
		_offbrd="border:"+_I[9]+";";
	if(_subC||_I[29]||(_M[4]&&horiz))
	{
		_Limg="";
		_Rimg="";
		_itrs="";
		_itre="";
		if(_I[3]&&_I[24])
		{
			_subIR=0;
			if(_M[11]=="rtl")
				_subIR=1;
			_oif="";
			if(op7)
				_oif=" onload=_oifx("+_el+") ";
			_img="<img id=simg"+_el+" src="+_I[24]+_oif+">";
			_simgP="";
			if(_I[22])
				_simgP=";padding:"+_I[22]+"px";
			_imps="width=1";
			if(_I[23])
			{
				_iA="width=1";
				_ivA="";
				_imP=_I[23].split(" ");
				for(_ia=0;_ia<_imP.length;_ia++)
				{
					if(_imP[_ia]=="left")
						_subIR=1;
					if(_imP[_ia]=="right")
						_subIR=0;
					if(_imP[_ia]=="top"||_imP[_ia]=="bottom"||_imP[_ia]=="middle")
					{
						_ivA="valign="+_imP[_ia];
						if(_imP[_ia]=="bottom")
							_subIR=0;
					}
					if(_imP[_ia]=="center")
					{
						_itrs="<tr>";
						_itre="</tr>";
						_iA="align=center width=100%";
					}
				}
				_imps=_iA+" "+_ivA;
			}
			_its=_itrs+"<td "+_imps+" style=\"font-size:1px"+_simgP+"\">";
			_ite="</td>"+_itre;
			if(_subIR)
			{
				_Limg=_its+_img+_ite;
			}
			else
			{
				_Rimg=_its+_img+_ite;
			}
		}
		if(_M[4])
			_iw="width="+_M[4];
		if(_iw==""&&!_I[1])
			_iw="width=1";
		if(_I[55])
			_iw="width="+_I[55];
		if(!horiz)
			_iw="width=100%";
		if(_M[18])
		{
			_iheight="style=\"height:"+_M[18]+"px;\"";
		}
		if(_I[28])
		{
			_iheight="style=\"height:"+_I[28]+"px;\"";
		}
		_mt+="<td id=el"+_el+" "+actiontext+" style=\""+_offbrd+_ofb+";\">";
		_mt+="<table border=0 cellpadding=0 cellspacing=0 "+_iheight+" "+_iw+" id=MTbl"+_el+">";
		_mt+="<tr id=td"+_el+" style=\""+_ofc+";\">";
		_mt+=_Limg;
		_mt+=_timg;
		_iw="width=100%";
		if(ie4||ns6)
			_iw="";
		if(_I[1])
		{
			_mt+="<td "+_iw+" "+_clss+" "+_nw+" id=tr"+_el+" style=\""+_ofc+_fsize+_ffam+_fweight+_fstyle+_tdec+";"+_padd+"\" "+_algn+">"+_link+" "+_I[1]+"</td>";
		}
		else
		{
			_mt+=_link;
		}
		_mt+=_bimg;
		_mt+=_Rimg;
		_mt+="</tr>";
		_mt+="</table>";
		_mt+="</td>";
	}
	else
	{
		if(_M[18])
			_iheight="height:"+_M[18]+"px;";
		if(_I[28])
			_iheight="height:"+_I[28]+"px;";
		_iw="";
		if(_I[55])
		{
			_iw="width="+_I[55];
			if(ns6)
				_link="<div style=\"width:"+_I[55]+"px;\">"+_link;
		}
		_mt+="<td nowrap "+_clss+" "+_iw+" "+_nw+" id=el"+_el+" "+actiontext+" "+_algn+" style=\""+_offbrd+_iheight+_ofc+_fsize+_ffam+_fweight+_fstyle+_tdec+";"+_ofb+";"+_padd+"\">"+_link+" "+_I[1]+"</td>";
	}
	if((_M[0][_i]!=_M[0][_M[0].length-1])&&_I[27]>0)
	{
		_sepadd="";
		_brd="";
		_sbg=";background:"+_I[10];
		if(_I[71])
			_sbg=";background-image:url("+_I[71]+");";
		if(_I[27])
		{
			if(horiz)
			{
				if(_I[49])
				{
					_sepA="middle";
					if(_I[52])
						_sepA=_I[52];
					if(_I[51])
						_sepadd="style=\"padding:"+_I[51]+"px;\"";
					_mt+="<td nowrap "+_sepadd+" valign="+_sepA+" align=left width=1><div style=\"font-size:1px;width:"+_I[27]+";height:"+_I[49]+";"+_brd+_sbg+";\"></div></td>";
				}
				else
				{
					if(_I[16]&&_I[17])
					{
						_bwid=_I[27]/2;
						if(_bwid<1)
							_bwid=1;
						_brdP=_bwid+"px solid ";
						_brd+="border-right:"+_brdP+_I[16]+";";
						_brd+="border-left:"+_brdP+_I[17]+";";
						if(mac||sfri||(ns6&&!ns7))
						{
							_mt+="<td style=\"width:"+_I[27]+"px;empty-cells:show;"+_brd+"\"></td>";
						}
						else
						{
							_mt+="<td style=\"empty-cells:show;"+_brd+"\"><table border=0 cellpadding=0 cellspacing=0><td></td></table></td>";
						}
					}
					else
					{
						if(_I[51])
							_sepadd="<td nowrap width="+_I[51]+"></td>";
						_mt+=_sepadd+"<td style=\"width:"+_I[27]+"px;"+_brd+_sbg+"\"><table border=0 cellpadding=0 cellspacing=0 width="+_I[27]+"><td></td></table></td>"+_sepadd;
					}
				}
			}
			else
			{
				if(_I[16]&&_I[17])
				{
					_bwid=_I[27]/2;
					if(_bwid<1)
						_bwid=1;
					_brdP=_bwid+"px solid ";
					_brd="border-bottom:"+_brdP+_I[16]+";";
					_brd+="border-top:"+_brdP+_I[17]+";";
					if(mac||ns6||sfri)
						_I[27]=0;
				}
				if(_I[51])
					_sepadd="<tr><td height="+_I[51]+"></td></tr>";
				_sepW="100%";
				if(_I[50])
					_sepW=_I[50];
				_sepA="center";
				if(_I[52])
					_sepA=_I[52];
				if(!mac)
					_sbg+=";overflow:hidden";
				_mt+="</tr>"+_sepadd+"<tr><td align="+_sepA+"><div style=\""+_sbg+";"+_brd+"width:"+_sepW+";height:"+_I[27]+"px;font-size:1px;\"></div></td></tr>"+_sepadd+"";
			}
		}
	}
}

function _fixMenu(_mnu)
{
	_gmt=gmobj("tbl"+_mnu);
	_gm=gmobj("menu"+_mnu);

	if(op5)
		_gm.style.pixelWidth=_gmt.style.pixelWidth+(_m[_mnu][12]*2)+(_m[_mnu][6][65]*2);
	if((ie4)||_m[_mnu][14]=="relative")
		_gm.style.width=_gmt.offsetWidth+"px";
	if(konq)
		_gm.style.width=_gmt.offsetWidth;
}

function getEVT(evt,_mnu)
{
	if(evt.target.tagName=="TD"){_egm=gmobj("menu"+_mnu);
	gevent=evt.layerY-(evt.pageY-_d.body.offsetTop)+_egm.offsetTop;}
}

function _drawMenu(_mnu,_begn)
{
	_mcnt++;
	var _M=_m[_mnu];
	_top="";
	_left="";
	if(!_M[14]&&!_M[7]){_top="top:-999px";
	_left="left:-999px"}if(_M[2]!=_n){if(!isNaN(_M[2]))_top="top:"+_M[2]+"px"}if(_M[3]!=_n){if(!isNaN(_M[3]))_left="left:"+_M[3]+"px"}_mnuHeight="";
	if(_M[9]=="horizontal"||_M[9]==1){_M[9]=1;
	horiz=1;
	if(_M[18]){_mnuHeight="height="+_M[18]}}else{_M[9]=0;
	horiz=0}_visi="hidden";
	_mt="";
	_nw="";
	_MS=_mi[_M[0][0]];
	_tablewidth="";
	if(_M[4]){_tablewidth="width="+_M[4];
	if(op7&&!IEDtD)_tablewidth="width="+(_M[4]-(_M[12]*2)-(_MS[65]*2))}else{if(!_M[17])_nw="nowrap"}_ofb="";
	if(_MS[7])_ofb="background:"+_MS[7];
	_brd="";
	_brdP="";
	_brdwid="";
	if(_MS[65]){_brdsty="solid";
	if(_MS[64])_brdsty=_MS[64];
	_brdcol="none";
	if(_MS[63])_brdcol=_MS[63];
	if(_MS[65])_brdwid=_MS[65];
	_brdP=_brdwid+"px "+_brdsty+" ";
	_brd="border:"+_brdP+_brdcol+";"}
	if(_MS[16]&&_MS[17]){_h3d=_MS[16];_l3d=_MS[17];
	if(_MS[70]){_h3d=_MS[17];
	_l3d=_MS[16]}_brdP=_brdwid+"px solid ";
	_brd="border-bottom:"+_brdP+_h3d+";";
	_brd+="border-right:"+_brdP+_h3d+";";
	_brd+="border-top:"+_brdP+_l3d+";";
	_brd+="border-left:"+_brdP+_l3d+";"}_ns6ev="";
	if(_M[13]=="scroll"&&ns6&&!ns7)_ns6ev="onmousemove=\"getEVT(event,"+_mnu+")\"";
	_bgimg="";
	if(_MS[73])_bgimg=";background-image:url("+_MS[73]+");";
	_posi="absolute";
	if(_M[14]){_posi=_M[14];
	if(_M[14]=="relative"){_posi="";
	if(!_M[4])_wid="width:1px;";
	_top="";
	_left=""}}_padd="";
	if(_M[12])_padd="padding:"+_M[12]+"px;";
	_wid="";
	_cls="mmenu";
	if(_MS[54])_cls=_MS[54];
	if(_posi)_posi="position:"+_posi;
	_mnwid="";
	if(_M[17])_mnwid="width="+_M[17];
	if(_begn==1){if(!op6&&_mnwid&&!ns7)_wid=";width:"+_M[17]+";";
	_mt+="<div class="+_cls+" onselectstart=\"return _f\" "+_ns6ev+" onmouseout=\"_CAMs()\" onmouseover=\"$CtI(_MT);\" id=menu"+_mnu+" style=\""+_padd+_ofb+";"+_brd+_wid+"z-index:99;visibility:"+_visi+";"+_posi+";"+_top+";"+_left+_bgimg+"\">"}_mali="";
	if(_M[20])_mali="align="+_M[20];
	if(_mnwid)_mt+="<table border=0 cellpadding=0 cellspacing=0 "+_mnwid+" style=\""+_ofb+"\"><td "+_mnwid+" "+_mali+">";
	_mt+="<table border=0 cellpadding=0 cellspacing=0 "+_mnuHeight+" "+_tablewidth+" id=tbl"+_mnu+">";
	for(_b=0;
	_b<_M[0].length;
	_b++){drawItem(_b);
	_el++}if(mac)_mt+="<tr><td id=btm"+_mnu+"></td></tr>";
	_mt+="</tr></table>";
	if(_mnwid)_mt+="</td></tr></table>";
	if(_begn==1)_mt+="</div>";

	// For debugging the menu script
	//document.all.debug.value += _mt;

	if(_begn==1)
		_d.write(_mt);
	else 
		return _mt;

	_M[22]=gmobj("menu"+_mnu);
	if(_M[7]){if(ie55)drawiF(_mnu)}else{if(ie55&&_ifc<_mD)drawiF(_mnu);
	_ifc++}if(_M[19]){_M[19]=_M[19].toString();
	_fs=_M[19].split(",");
	if(!_fs[1])_fs[1]=50;
	if(!_fs[2])_fs[2]=2;
	_M[19]=_fs[0];
	followScroll(_mnu,_fs[1],_fs[2])}if(_mnu==_m.length-1){$CtI(_mst);
	_mst=null;
	_mst=setTimeout("_MScan()",150);
	_getCurPath();}
}

function _getCurPath()
{
	_cmp=new Array();
	if(_cip.length>0){for(_c=0;
	_c<_cip.length;
	_c++){_ci=_cip[_c];
	_mni=getParentItemByItem(_ci);
	if(_mni==-1)_mni=_ci;
	if(_mni+" "!="undefined "){while(_mni!=-1){_I=_mi[_mni];
	_setCPage(_I);
	itemOff(_mni);
	_cmp[_cmp.length]=_mni;
	_mni=getParentItemByItem(_mni);
	if(_mni+" "=="undefined ")_mni=-1;}}}}
}

function _setPosition(_mnu)
{
	if(_m[_mnu][5]){_gm=gmobj("menu"+_mnu);
	_gp=gpos(_gm);
	_osl=0;
	_omnu3=0;
	if(isNaN(_m[_mnu][3])&&_m[_mnu][3].indexOf("offset=")==0){_omnu3=_m[_mnu][3];
	_m[_mnu][3]=_n;
	_osl=_omnu3.substr(7,99);
	_gm.leftOffset=_osl}_lft=_n;
	if(!_m[_mnu][3]){if(_m[_mnu][5].indexOf("left")!=-1)_lft=0;
	if(_m[_mnu][5].indexOf("center")!=-1)_lft=(_bW/2)-(_gp[3]/2);
	if(_m[_mnu][5].indexOf("right")!=-1)_lft=_bW-_gp[3];
	if(_gm.leftOffset)_lft=_lft+parseInt(_gm.leftOffset)}_ost=0;
	_omnu2=0;
	if(isNaN(_m[_mnu][2])&&_m[_mnu][2].indexOf("offset=")==0){_omnu2=_m[_mnu][2];
	_m[_mnu][2]=_n;
	_ost=_omnu2.substr(7,99);
	_gm.topOffset=_ost}_tp=_n;
	if(!_m[_mnu][2]>=0){_tp=_n;
	if(_m[_mnu][5].indexOf("top")!=-1)_tp=0;
	if(_m[_mnu][5].indexOf("middle")!=-1)_tp=(_bH/2)-(_gp[2]/2);
	if(_m[_mnu][5].indexOf("bottom")!=-1)_tp=_bH-_gp[2];
	if(_gm.topOffset)_tp=_tp+parseInt(_gm.topOffset)}spos(_gm,_tp,_lft);
	if(_m[_mnu][19])_m[_mnu][19]=_tp;
	if(_m[_mnu][7])_SoT(_mnu,1);
	_gm._tp=_tp;}
}

function followScroll(_mnu,_cycles,_rate)
{
	if(!_startM){_M=_m[_mnu];
	_fogm=_M[22];
	_fgp=gpos(_fogm);
	if(_sT>_M[2]-_M[19])_tt=_sT-(_sT-_M[19]);
	else _tt=_M[2]-_sT;
	if((_fgp[0]-_sT)!=_tt){diff=_sT+_tt;
	if(diff-_fgp[0]<1)_rcor=_rate;
	else _rcor=-_rate;
	_nv=parseInt((diff-_rcor-_fgp[0])/_rate);
	if(_nv!=0)diff=_fgp[0]+_nv;
	spos(_fogm,diff);
	if(_fgp._tp)_M[19]=_fgp._tp;
	if(ie55){_fogm=gmobj("ifM"+_mnu);
	if(_fogm)spos(_fogm,diff)}}}_fS=setTimeout("followScroll(\""+_mnu+"\","+_cycles+","+_rate+")",_cycles);
}

function _MScan()
{
	_getDims();
	if(_bH!=_oldbH||_bW!=_oldbW){for(_a=0;
	_a<_m.length;
	_a++){if(_m[_a][7]){if(_startM&&(ie4||_m[_a][14]=="relative")){_fixMenu(_a)}menuDisplay(_a,1)}}for(_a=0;
	_a<_m.length;
	_a++){if(_m[_a][5]){_setPosition(_a)}}}if(_startM)_mnuD=0;
	_startM=0;
	_oldbH=_bH;
	_oldbW=_bW;
	if(!op&&_d.all&&_d.readyState!="complete"){_oldbH=0;
	_oldbW=0}if(op){}_mst=setTimeout("_MScan()",150);
}

function drawiF(_mnu)
{
	_gm=gmobj("menu"+_mnu);
	_gp=gpos(_gm);
	_ssrc="";
	if(location.protocol=="https:")_ssrc="src=/blank.html";
	if(_m[_mnu][7]){_mnuV="ifM"+_mnu}else{_mnuV="iF"+_mnuD;
	_mnuD++}if(!window._CFix)_d.write("<iframe class=mmenu FRAMEBORDER=0 id="+_mnuV+" "+_ssrc+" style=\"filter:Alpha(Opacity=0);visibility:hidden;position:absolute;top:"+_gp[0]+"px;left:"+_gp[1]+"px;height:"+_gp[2]+"px;width:"+_gp[3]+"px;\"></iframe>")}function _SoT(_mnu,_on){if(_m[_mnu][14]=="relative")return;
	if(ns6)return;
	if(ie55){if(_on){if(!_m[_mnu][7]){_iF=gmobj("iF"+_mnuD);
	if(!_iF){if(_d.readyState!="complete")return;
	_iF=_d.createElement("iframe");
	if(location.protocol=="https:")_iF.src="/blank.html";
	_iF.id="iF"+_mnuD;
	_iF.style.filter="Alpha(Opacity=0)";
	_iF.style.position="absolute";
	_iF.style.className="mmenu";
	_d.body.appendChild(_iF)}}else{_iF=gmobj("ifM"+_mnu)}_gp=gpos(_m[_mnu][22]);
	if(_iF){spos(_iF,_gp[0],_gp[1],_gp[2],_gp[3]);
	_iF.style.visibility="visible";
	_iF.style.zIndex=1}}else{_gm=gmobj("iF"+(_mnuD-1));
	if(_gm)_gm.style.visibility="hidden";}}
}
