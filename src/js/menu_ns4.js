/*
Javscript menu
NS4 stuff
*/

_amt="";
_MTF=0;
_onTS=0;
var _cel=-1;

function gmobj(mtxt)
{
	if(_d.layers[mtxt])
		return _d.layers[mtxt];
	re=/\d*\d/;
	fnd=re.exec(mtxt);
	if(_d.layers["menu"+_mi[fnd][0]])
	{
		return _d.layers["menu"+_mi[fnd][0]].document.layers["il"+fnd].document.layers[mtxt];
	}
	else
	{
		return document.layers["il"+fnd].document.layers[mtxt];
	}
}

function spos(gm,t_,l_,h_,w_)
{
	if(t_!=null)
		gm.top=t_;
	if(l_!=null)
		gm.left=l_;
	if(h_!=null)
		gm.height=h_;
	if(w_!=null)
		gm.width=w_;
}

function gpos(gm)
{
	var gpa=new Array();
	gpa[0]=gm.pageY;
	gpa[1]=gm.pageX;
	gpa[2]=gm.clip.height;
	gpa[3]=gm.clip.width;
	return(gpa)
}

function _lc(_dummy)
{
	if(window.retainClickValue)
		inopenmode=1;
	
	_i=nshl;
	if(_mi[_i][62])
		eval(_mi[_i][62]);
	if(_i>-1)
	{
		if(_mi[_i][2])
		{
			location.href=_mi[_i][2];
		}
		else
		{
		if(_mi[_i][39]||_mi[_i][40])
		{
			_nullLink(_i);
		}
	}
}
}

function _nullLink(_i)
{if(_mi[_i][3]){_oldMC=_mi[_i][39];
_mi[_i][39]=0;
_oldMD=_menuOpenDelay;
_menuOpenDelay=0;
_gm=gmobj("menu"+getMenuByName(_mi[_i][3]));
if(_gm.visibility=="show"&&_mi[_i][40]){menuDisplay(getMenuByName(_mi[_i][3]),0);
itemOn(_i)}else{_popi(_i)}_menuOpenDelay=_oldMD;
_mi[_i][39]=_oldMC}
}

function itemOn(_i)
{clearTimeout(_scrmt);
if(_mi[_i][34]=="header"||_mi[_i][34]=="form")return;
_gm=gmobj("oel"+_i);
_gm.visibility="show";
if(_mi[_i][42])eval(_mi[_i][42])
}

function itemOff(_i)
{if(_i>-1){_gm=gmobj("oel"+_i);
_gm.visibility="hide";
if(_mi[_i][43])eval(_mi[_i][43])}}_NS4S=new Array();
function drawItem(_i){_Tmt="";
_Dmnu=_mi[_i][0];
var _M=_m[_Dmnu];
var _mE=_mi[_i];
if(!_NS4S[_i]){if(!_mi[_i][33])_mi[_i][33]="none";
if(!_mi[_i][26])_mi[_i][26]="none";
if(!_mi[_i][14])_mi[_i][14]="normal";
_st=".item"+_i+"{";
if(_mi[_i][33])_st+="textDecoration:"+_mi[_i][33]+";";
if(_mi[_i][15])_st+="fontFamily:"+_mi[_i][15]+";";
if(_mi[_i][14])_st+="fontWeight:"+_mi[_i][14]+";";
if(_mi[_i][12])_st+="fontSize:"+_mi[_i][12]+";";
_st+="}";
_st+=".oitem"+_i+"{";
if(_mi[_i][15])_st+="fontFamily:"+_mi[_i][15]+";";
if(_mi[_i][14])_st+="fontWeight:"+_mi[_i][14]+";";
if(_mi[_i][33])_st+="textDecoration:"+_mi[_i][33]+";";
if(_mi[_i][44])_st+="fontWeight:bold;";
if(_mi[_i][45])_st+="fontStyle:italic;";
if(_mi[_i][12])_st+="fontSize:"+_mi[_i][12]+";";
if(_mi[_i][26])_st+="textDecoration:"+_mi[_i][26]+";";
_st+="}";
_d.write("<style>"+_st+"</style>");
_NS4S[_i]=_i}_lnk="javascript:_nullLink("+_i+");";
if(_mi[_i][2])_lnk="javascript:_lc("+_i+")";
_wid="";
if(_M[4])_wid="width="+_M[4];
if(_mi[_i][55])_wid="width="+_mi[_i][55];
_hgt="";
if(_M[18]){_hgt="height="+_M[18]}if(_mi[_i][28]){_hgt="height="+_mi[_i][28]}_pad="0";
if(_mE[11])_pad=_mE[11];
if(_mi[_i][34]=="header"){if(_mi[_i][20])_mi[_i][8]=_mi[_i][20];
if(_mi[_i][20])_mi[_i][7]=_mi[_i][21]}_bgc="";
if(_mi[_i][7]=="transparent")_mi[_i][7]=_n;
if(_mi[_i][7])_bgc="bgcolor="+_mi[_i][7];
_fgc="";
if(_mi[_i][8])_fgc="<font color="+_mi[_i][8]+">";
_bgbc="";
if(_mi[_i][5])_bgbc="bgcolor="+_mi[_i][5];
_fgbc="";
if(_mi[_i][6])_fgbc="<font color="+_mi[_i][6]+">";
_algn="";
if(_M[8])_algn=" align="+_M[8];
if(_mi[_i][36])_algn=" align="+_mi[_i][36];
if(_mi[_i][61])_algn=" valign="+_mi[_i][61];
_nw="";
if(!_M[4]&&!_mi[_i][55])_nw=" nowrap ";
_iMS="";
_iME="";
if(_lnk){_iMS="<a href=\""+_lnk+"\" onMouseOver=\"set_status("+_i+");return true\">";
_iME="</a>"}_Lsimg="";
_Rsimg="";
_LsimgO="";
_RsimgO="";
_itrs="";
_itre="";
if(_mi[_i][3]&&_mi[_i][24]){_subIR=0;
if(_M[11]=="rtl")_subIR=1;
_img=_iMS+"<img border=0 src="+_mi[_i][24]+">"+_iME;
_oimg=_img;
if(_mi[_i][48])_oimg=_iMS+"<img border=0 src="+_mi[_i][48]+">"+_iME;
_simgP="";
if(_mi[_i][22])_simgP=_mi[_i][22];
_imps="";
if(_mi[_i][23]){_iA="";
_ivA="";
_imP=_mi[_i][23].split(" ");
for(_ia=0;
_ia<_imP.length;
_ia++){if(_imP[_ia]=="left")_subIR=1;
if(_imP[_ia]=="right")_subIR=0;
if(_imP[_ia]=="top"||_imP[_ia]=="bottom"||_imP[_ia]=="middle"){_ivA="valign="+_imP[_ia];
if(_imP[_ia]=="top")_subIR=1;
if(_imP[_ia]=="bottom")_subIR=0}if(_imP[_ia]=="center"){_itrs="<tr>";
_itre="</tr>";
_iA="align=center"}}_imps=_iA+" "+_ivA}_its=_itrs+"<td "+_imps+"><table border=0 cellspacing="+_simgP+" cellpadding=0><td>";
_ite="</td></table></td>"+_itre;
if(_subIR)_Lsimg=_its+_img+_ite;
else _Rsimg=_its+_img+_ite;
if(_subIR)_LsimgO=_its+_oimg+_ite;
else _RsimgO=_its+_oimg+_ite}_Limg="";
_Rimg="";
_LimgO="";
_RimgO="";
if(_mi[_i][29]){_iA="";
_ivA="";
_imps="";
_Iwid="";
if(_mi[_i][37])_Iwid=" width="+_mi[_i][37];
_Ihgt="";
if(_mi[_i][38])_Ihgt=" height="+_mi[_i][38];
_img=_iMS+"<img "+_Iwid+_Ihgt+" border=0 src="+_mi[_i][29]+">"+_iME;
_oimg=_img;
if(_mi[_i][32])_oimg=_iMS+"<img "+_Iwid+_Ihgt+" border=0 src="+_mi[_i][32]+">"+_iME;
if(!_mi[_i][30])_mi[_i][30]="left";
_imP=_mi[_i][30].split(" ");
for(_ia=0;
_ia<_imP.length;
_ia++){if(_imP[_ia]=="left")_subIR=1;
if(_imP[_ia]=="right")_subIR=0;
if(_imP[_ia]=="top"||_imP[_ia]=="bottom"||_imP[_ia]=="middle"){_ivA="valign="+_imP[_ia];
if(_mi[_i][3])_ivA+=" colspan=2";
if(_imP[_ia]=="top")_subIR=1;
if(_imP[_ia]=="bottom")_subIR=0}if(_imP[_ia]=="center"){_itrs="<tr>";
_itre="</tr>";
_iA="align=center"}}_imps=_iA+" "+_ivA;
_its=_itrs+"<td "+_imps+"><table border=0 cellspacing=0 cellpadding=0><tr><td>";
_ite="</td></tr></table></td>"+_itre;
if(!_mi[_i][1]){_its="";
_ite=""}if(_subIR)_Limg=_its+_img+_ite;
else _Rimg=_its+_img+_ite;
if(_subIR)_LimgO=_its+_oimg+_ite;
else _RimgO=_its+_oimg+_ite}if(!_M[9]){_Tmt+="<tr>"}_Tmt+="<td  class=item"+_i+">";
_Tmt+="<ilayer id=il"+_i+">";
_txt="";
if(_mi[_i][1])_txt=_mi[_i][1];
_acT="onmouseover=\"_popi("+_i+");clearTimeout(_MTF);_MTF=setTimeout('close_el("+_i+")',200);\";drag_drop('menu"+_Dmnu+"');";
if(_mi[_i][34]=="dragable"){}if(_mi[_i][34]=="header")_acT="";
_Tmt+="<layer id=el"+_i+" "+_acT+" width=100%>";
_Tmt+="<div></div>";
_Tmt+="<table "+_wid+" "+_bgc+" border=0 cellpadding=0 cellspacing=0 width=100%>";
_Tmt+=_Limg;
_Tmt+=_Lsimg;
if(_txt){_Tmt+="<td width=100%><table border=0 cellpadding="+_pad+" cellspacing=0 width=100%><td "+_hgt+" "+_algn+_nw+" >";
_Tmt+="<a href=\"\" class=item"+_i+" onMouseOver=\"set_status("+_i+");return true\">";
_Tmt+=_fgc+_txt;
_Tmt+="</a>";
_Tmt+="</td></table></td>"}_Tmt+=_Rimg;
_Tmt+=_Rsimg;
_Tmt+="</table>";
_Tmt+="</layer>";
_Tmt+="<layer visibility=hide id=oel"+_i+" zindex=999 onMouseOver=\"clearTimeout(_MTF);_back2par("+_i+");nshl="+_i+";this.captureEvents(Event.MOUSEUP);this.onMouseUp=_lc;\" onMouseOut=\"close_el("+_i+")\"width=100%>";
_Tmt+="<div></div>";
_Tmt+="<table "+_wid+" "+_bgbc+" border=0 cellpadding=0 cellspacing=0 width=100%>";
_Tmt+=_LimgO;
_Tmt+=_LsimgO;
if(_txt){_Tmt+="<td height=1 width=100%><table border=0 cellpadding="+_pad+" cellspacing=0 width=100%><td "+_hgt+" "+_algn+_nw+" >";
_Tmt+="<a class=oitem"+_i+" href=\""+_lnk+"\" onMouseOver=\"set_status("+_i+");
return true\">";
_Tmt+=_fgbc+_txt;
_Tmt+="</a>";
_Tmt+="</td></table></td>"}_Tmt+=_RimgO;
_Tmt+=_RsimgO;
_Tmt+="</table>";
_Tmt+="</layer>";
_Tmt+="</ilayer>";
_Tmt+="</td>";
_hgt="";
if(_M[18]){_hgt="height="+(_M[18]+6);
_hgt="height=20"}_spd="";
if(_mi[_i][51])_spd=_mi[_i][51];
_sal="align=center";
if(_mi[_i][52])_sal="align="+_mi[_i][52];
_sbg="";
if(_mi[_i][71])_sbg="background="+_mi[_i][71];
if(!_M[9]){_Tmt+="</tr>";
if((_i!=_M[0][_M[0].length-1])&&_mi[_i][27]>0){_swid="100%";
if(_mi[_i][50])_swid=_mi[_i][50];
if(_spd)_Tmt+="<tr><td height="+_spd+"></td></tr>";
_Tmt+="<tr><td "+_sal+"><table cellpadding=0 cellspacing=0 border=0 width="+_swid+">";
if(_mi[_i][16]&&_mi[_i][17]){_bwid=_mi[_i][27]/2;
if(_bwid<1)_bwid=1;
_Tmt+="<tr><td bgcolor="+_mi[_i][17]+">";
_Tmt+="<spacer type=block height="+_bwid+"></td></tr>";
_Tmt+="<tr><td bgcolor="+_mi[_i][16]+">";
_Tmt+="<spacer type=block height="+_bwid+"></td></tr>"}else{_Tmt+="<td "+_sbg+" bgcolor="+_mi[_i][10]+">";
_Tmt+="<spacer type=block height="+_mi[_i][27]+"></td>"}_Tmt+="</table></td></tr>";
if(_spd)_Tmt+="<tr><td height="+_spd+"></td></tr>"}}else{if((_i!=_M[0][_M[0].length-1])&&_mi[_i][27]>0){_hgt="height=100%";
if(_mi[_i][16]&&_mi[_i][17]){_bwid=_mi[_i][27]/2;
if(_bwid<1)_bwid=1;
_Tmt+="<td bgcolor="+_mi[_i][17]+"><spacer type=block "+_hgt+" width="+_bwid+"></td>";
_Tmt+="<td bgcolor="+_mi[_i][16]+"><spacer type=block "+_hgt+" width="+_bwid+"></td>"}else{if(_spd)_Tmt+="<td><spacer type=block width="+_spd+"></td>";
_Tmt+="<td "+_sbg+" bgcolor="+_mi[_i][10]+"><spacer type=block "+_hgt+" width="+_mi[_i][27]+"></td>";
if(_spd)_Tmt+="<td><spacer type=block width="+_spd+"></td>"}}}return _Tmt
}

function csto(_mnu)
{_onTS=0;
clearTimeout(_scrmt);
clearTimeout(_oMT);
_MT=setTimeout("closeAllMenus()",_menuCloseDelay)
}

function followScroll(_mnu,_cycles,_rate)
{if(!_startM){_M=_m[_mnu];
_fogm=_M[22];
_fgp=gpos(_fogm);
if(_sT>_M[2]-_M[19])_tt=_sT-(_sT-_M[19]);
else _tt=_M[2]-_sT;
_tt+=_M[6][65];
if((_fgp[0]-_sT)!=_tt){diff=_sT+_tt;
if(diff-_fgp[0]<1)_rcor=_rate;
else _rcor=-_rate;
_nv=parseInt((diff-_rcor-_fgp[0])/_rate);
if(_nv!=0)diff=_fgp[0]+_nv;
spos(_fogm,diff);
if(_fgp._tp)_M[19]=_fgp._tp;
_fgp=gpos(_fogm);
spos(gmobj("bord"+_mnu),_fgp[0]-_m[_mnu][6][65])}}_fS=setTimeout("followScroll(\""+_mnu+"\","+_cycles+","+_rate+")",_cycles)
}

function _drawMenu(_mnu)
{_mt="";
_mcnt++;
var _M=_m[_mnu];
_ms=_m[_mnu][6];
if(_M[9]=="horizontal")_M[9]=1;
else _M[9]=0;
_visi="";
if(!_M[7])_visi="visibility=hide";
_top="top=0";
if(_M[2])_top="top="+_M[2];
_left="left=0";
if(_M[3])_left="left="+_M[3];
if(_M[9]){_oldBel=_Bel;
_d.write("<layer visibility=hide id=HT"+_mnu+"><table border=0 cellpadding=0 cellspacing=0>");
for(_b=0;
_b<_M[0].length;
_b++){_d.write(drawItem(_Bel));
_Bel++}_d.write("</table></layer>");
_Bel=_oldBel;
_gm=gmobj("HT"+_mnu);
_M[18]=_gm.clip.height-6}_bImg="";
if(_M[6][46])_bImg="background="+_M[6][46];
if(_M[14]!="relative")_mt+="<layer zindex=999 "+_bImg+" onmouseout=\"close_menu()\" onmouseover=\"clearTimeout(_MT);\" id=menu"+_mnu+" "+_top+" "+_left+" "+_visi+">";_bgc="";if(_m[_mnu][6].offbgcolor=="transparent")_m[_mnu][6].offbgcolor=_n;if(_m[_mnu][6].offbgcolor)_bgc="bgcolor="+_m[_mnu][6].offbgcolor;_mrg=0;if(_M[12])_mrg=_M[12];_mt+="<table "+_bgc+" border=0 cellpadding="+_mrg+" cellspacing=0 >";_mt+="<td>";_mt+="<table width=1 border=0 cellpadding=0 cellspacing=0 "+_bgc+">";for(_b=0;_b<_M[0].length;_b++){_mt+=drawItem(_Bel);_Bel++}_mt+="</table>";_mt+="</td>";_mt+="</table>";if(_M[14]!="relative")_mt+="</layer>";_amt+=_mt;_d.write(_mt);_M[22]=gmobj("menu"+_mnu);if(_M[19]){_M[19]=_M[19].toString();_fs=_M[19].split(",");if(!_fs[1])_fs[1]=20;if(!_fs[2])_fs[2]=10;_M[19]=_fs[0];followScroll(_mnu,_fs[1],_fs[2])}if(_M[14]!="relative"){_st="";_brdsty="solid";if(_M[6].borderstyle)_brdsty=_M[6].borderstyle;if(_M[6][64])_brdsty=_M[6][64];_brdcol="#000000";if(_M[6].bordercolor)_brdcol=_M[6].bordercolor;if(_M[6][63])_brdcol=_M[6][63];_brdwid="";if(_M[6].borderwidth)_brdwid=_M[6].borderwidth;if(_M[6][65])_brdwid=_M[6][65];_M[6][65]=_brdwid;_st=".menu"+_mnu+"{";_st+="borderStyle:"+_brdsty+";";
_st+="borderColor:"+_brdcol+";";
_st+="borderWidth:"+_brdwid+";";
if(_ms.fontsize)_st+="fontSize:"+2+";";
_st+="}";
_d.write("<style>"+_st+"</style>");
_gm=gmobj("menu"+_mnu);
_d.write("<layer visibility=hidden id=bord"+_mnu+" zindex=0 class=menu"+_mnu+"><table width="+(_gm.clip.width-6)+" height="+(_gm.clip.height-6)+"><td></td></table></layer>");
if(_M[7]){_gm=gmobj("menu"+_mnu);
_gm.zIndex=999;
_gp=gpos(_gm);
spos(_gm,_gp[0]+_M[6][65],_gp[1]+_M[6][65],_gp[2],_gp[3]);
_gmb=gmobj("bord"+_mnu);
_gmb.zIndex=0;
spos(_gmb,_gp[0],_gp[1],_gp[2],_gp[3]);
_gmb.visibility="show"}}else{}if(_m[_mnu][13]=="scroll"){_gm=gmobj("menu"+_mnu);
_gm.fullHeight=_gm.clip.height;
_scs=";
this.bgColor='"+_m[_mnu][6].onbgcolor+"'\" onmouseout=\"csto("+_mnu+");
this.bgColor='"+_m[_mnu][6].offbgcolor+"'\"";
_scs+=" visibility=hide "+_bgc+" class=menu"+_mnu+"><table border=0 cellpadding=0 cellspacing=0 width="+(_gm.clip.width-6)+"><td align=center>";
_sce="</td></table></layer>";
_d.write("<layer id=tscroll"+_mnu+" onmouseover=\"_is("+_mnu+","+_scrollAmount+");"+_scs+"<img src=images/uparrow.gif>"+_sce);
_d.write("<layer id=bscroll"+_mnu+" onmouseover=\"_is("+_mnu+",-"+_scrollAmount+");"+_scs+"<img src=images/uparrow.gif>"+_sce);
_ts=gmobj("tscroll"+_mnu);
_gm.tsHeight=_ts.clip.height;
_ts=gmobj("bscroll"+_mnu);
_gm.bsHeight=_ts.clip.height}
}

function getMenuByItem(_gel)
{_gel=_mi[_gel][0];
if(_m[_gel][7])_gel=-1;
return _gel
}

function getParentMenuByItem(_gel)
{_tm=getMenuByItem(_gel);
if(_tm==-1)return-1;
for(_x=0;
_x<_mi.length;
_x++){if(_mi[_x][3]==_m[_tm][1]){return _mi[_x][0]}}return-1
}

function getParentItemByItem(_gel)
{_tm=getMenuByItem(_gel);
if(_tm==-1)return-1;
for(_x=0;
_x<_mi.length;
_x++){if(_mi[_x][3]==_m[_tm][1]){return _x}}return-1
}

function _setPath(_mpi)
{if(_mpi>-1){_ci=_m[_mpi][21];
while(_ci>-1){itemOn(_ci);
_ci=_m[_mi[_ci][0]][21]}}
}

function _back2par(_i)
{if(_oldel>-1){if(_i==_m[_mi[_oldel][0]][21]){_popi(_i)}}
}

function closeMenusByArray(_ar)
{for(_a=0;
_a<_ar.length;
_a++){menuDisplay(_ar[_a],0)}
}

function cm()
{_tar=getMenusToClose();
closeMenusByArray(_tar);
for(_b=0;
_b<_tar.length;
_b++){if(_tar[_b]!=_mnu)_sm=remove(_sm,_tar[_b])}
}

function getMenusToClose()
{_st=-1;
_en=_sm.length;
_mm=_iP;
if(_iP==-1){if(_sm[0]!=_masterMenu)return _sm;
_mm=_masterMenu}for(_b=0;
_b<_sm.length;
_b++){if(_sm[_b]==_mm)_st=_b+1;
if(_sm[_b]==_mnu)_en=_b}if(_st>-1&&_en>-1){_tsm=_sm.slice(_st,_en)}return _tsm
}

function getMenuByName(_mname)
{_mname=$tL(_mname);
for(_gma=0;
_gma<_m.length;
_gma++){if(_mname==_m[_gma][1]){return _gma}}return-1
}

function clearELs(_i)
{_mnu=_mi[_i][0];
for(_q=0;
_q<_m[_mnu][0].length;
_q++){gmobj("oel"+_m[_mnu][0][_q]).visibility="hide"}
}

function menuDisplay(_mnu,_show)
{_gm=gmobj("menu"+_mnu);
_gmb=gmobj("bord"+_mnu);
M_hideLayer(_mnu,_show);
for(_q=0;
_q<_m[_mnu][0].length;
_q++){gmobj("oel"+_m[_mnu][0][_q]).visibility="hide"}if(_show){_gm.zIndex=_zi;
_gm.visibility="show";
_gmb.top=_gm.pageY-_m[_mnu][6][65];
_gmb.left=_gm.pageX-_m[_mnu][6][65];
_gmb.zIndex=_zi-1;
_gmb.visibility="show";
if(_el>-1)_m[_mnu][21]=_el;
if(_m[_mnu][13]=="scroll"){if((_gm.clip.height>_bH)||_gm.nsDoScroll){_gi=gmobj("el"+_el);
_tsm=gmobj("tscroll"+_mnu);
_bsm=gmobj("bscroll"+_mnu);
if(!_gm.scrollTop)_gm.top=_gm.top+_tsm.clip.height-1;
else _gm.top=_gm.scrollTop;
_gm.clip.height=_bH-(_gi.pageY+_gi.clip.height)-19;
_gmb.clip.height=_gm.clip.height;
_tsm.top=_gmb.top;
_tsm.left=_gmb.left;
_tsm.zIndex=_zi+1;
_bsm.left=_gmb.left;
_bsm.top=(_gmb.pageY+_gmb.clip.height)-_tsm.clip.height+_gm.tsHeight;
_tsm.zIndex=_zi+1;
_tsm.visibility="show";
_bsm.zIndex=_zi+1;
_bsm.visibility="show";
_gm.nsDoScroll=1}}}else{if(!(_m[_mnu][7])){_gm.visibility="hide";
_gmb.visibility="hide";
if(_m[_mnu][13]=="scroll"){_tsm=gmobj("tscroll"+_mnu);
_tsm.visibility="hide";
_tsm=gmobj("bscroll"+_mnu);
_tsm.visibility="hide"}}}
}

function forceCloseAllMenus()
{_cmo=gmobj("menu"+_mi[_cel][0]);
if(!_cmo)_cmo=gmobj("oel"+_cel);
for(_a=0;
_a<_m.length;
_a++){if(!_m[_a][7]&&!_m[_a][10])menuDisplay(_a,0)}_zi=999;
_el=-1
}

function closeAllMenus()
{_cmo=gmobj("menu"+_mi[_cel][0]);
if(!_cmo)_cmo=gmobj("oel"+_cel);
if(!_onTS&&_cmo&&(MouseX>(_cmo.pageX+_cmo.clip.width)||MouseY>(_cmo.pageY+_cmo.clip.height)||MouseX<_cmo.pageX||MouseY<_cmo.pageY)){inopenmode=0;
for(_ca=0;
_ca<_m.length;
_ca++){if(!_m[_ca][7]&&!_m[_ca][10])menuDisplay(_ca,0);
if(_m[_ca][21]>-1){itemOff(_m[_ca][21]);
_m[_ca][21]=-1}}_zi=999;
_el=-1}
}

function close_menu()
{if(_el==-1)_MT=setTimeout("closeAllMenus()",_menuCloseDelay)
}

function close_el(_i)
{if(_mi[_i][43])eval(_mi[_i][43]);
clearELs(_i);
window.status="";
clearTimeout(_oMT);
_MT=setTimeout("closeAllMenus()",_menuCloseDelay);
_el=-1;
_oldel=_i
}

function getParentMenuByItem(_gel)
{_gel=_mi[_gel][0];
if(_m[_gel][7])_gel=-1;
return _gel
}

function getParentItemByItem(_gel)
{_par=getParentMenuByItem(_gel);
for(_a=0;
_a<_m[_par][0].length;
_a++){if(_gel==_m[_par][0][_a]){return _m[_par][0]}}return false
}

function getParentsByItem(_gmi)
{
}

function lc(_i)
{if(_mi[_i]=="disabled")return;
location.href=_mi[_i][2]
}

function _is(_mnu,_SCRam)
{_onTS=1;
_cel=_m[_mnu][0][0];
clearTimeout(_MT);
clearTimeout(_scrmt);
_doScroll(_mnu,_SCRam);
_scrmt=setTimeout("_is("+_mnu+","+_SCRam+")",_scrollDelay)
}

function _doScroll(_mnu,_SCRam){gm=gmobj("menu"+_mnu);
if(_SCRam<0&&((gm.clip.top+gm.clip.height)>gm.fullHeight+gm.tsHeight+_SCRam))return;
if(_SCRam>0&&gm.clip.top<_SCRam)return;
gm.top=gm.top+_SCRam;
gm.scrollTop=gm.top;
gm.clip.top=gm.clip.top-_SCRam;
gm.clip.height=gm.clip.height-_SCRam
}

function set_status(_i)
{if(_mi[_i][4]!=null){status=_mi[_i][4]}else{if(_mi[_i][2])status=_mi[_i][2];
else status=""}
}

function getOffsetValue(_ofs)
{_ofsv=0;
if(isNaN(_ofs)&&_ofs.indexOf("offset=")==0){_ofsv=parseInt(_ofs.substr(7,99))}return _ofsv
}

function popup()
{_sm=new Array;
_arg=arguments;
clearTimeout(_MT);
clearTimeout(_oMT);
if(_cel>-1)forceCloseAllMenus();
if(_arg[0]){_ofMT=0;
_mnu=getMenuByName(_arg[0]);
_cel=_m[_mnu][0][0];
_tos=0;
if(_arg[2])_tos=_arg[2];
_los=0;
if(_arg[3])_los=_arg[3];
_sm[_sm.length]=_mnu;
if(_arg[1]){_gm=gmobj("menu"+_mnu);
_gp=gpos(_gm);
if(_arg[1]==1){if(MouseY+_gp[2]>(_bH)+_sT)_tos=-(MouseY+_gp[2]-_bH)+_sT;
if(MouseX+_gp[3]>(_bW)+_sL)_los=-(MouseX+_gp[3]-_bW)+_sL;
if(_m[_mnu][2]){if(isNaN(_m[_mnu][2]))_tos=getOffsetValue(_m[_mnu][2]);
else{_tos=_m[_mnu][2];
MouseY=0}}if(_m[_mnu][3]){if(isNaN(_m[_mnu][3]))_los=getOffsetValue(_m[_mnu][3]);
else{_los=_m[_mnu][3];
MouseX=0}}if(ns6&&!ns60){_los-=_sL;
_tos-=_sT}spos(_gm,MouseY+_tos,MouseX+_los)}else{for(_a=0;
_a<_d.images.length;
_a++){if(_d.images[_a].name==_arg[1])_po=_d.images[_a]}spos(_gm,_po.y+_po.height+getOffsetValue(_m[_mnu][2]),_po.x+getOffsetValue(_m[_mnu][3]))}}menuDisplay(_mnu,1);
_m[_mnu][21]=-1}
}

function Opopup(_mn,_mp)
{clearTimeout(_MT);
closeAllMenus();
if(_mn){_mnu=getMenuByName(_mn);
_sm[_sm.length]=_mnu;
menuDisplay(_mnu,1);
_m[_mnu][21]=-1}
}

function popdown()
{_MT=setTimeout("closeAllMenus()",_menuCloseDelay)
}

function _popi(_i)
{_cel=_i;
clearTimeout(_MT);
clearTimeout(_cMT);
clearTimeout(_oMT);
if(_mi[_i][34]=="disabled")return;
clearELs(_i);
if(_oldel>-1)clearELs(_oldel);
_mnu=-1;
_el=_i;
_itemRef=_i;
_mopen=_mi[_i][3];
horiz=0;
if(_m[_mi[_i][0]][9])horiz=1;
itemOn(_i);
if(!_sm.length){_sm[_sm.length]=_mi[_i][0];
_masterMenu=_mi[_i][0]}_iP=getParentMenuByItem(_el);
if(_iP==-1)_masterMenu=_mi[_i][0];
set_status(_el);
_cMT=setTimeout("cm()",_menuOpenDelay);
if(_mopen&&(!_mi[_el][39]||inopenmode)){_gel=gmobj("el"+_i);
_gp=gpos(_gel);
_mnu=getMenuByName(_mopen);
if(_mi[_i][41])_m[_mnu][10]=1;
if(_mnu>-1){_gp=gpos(_gel);
_mnO=gmobj("menu"+_mnu);
_mp=gpos(_mnO);
if(horiz){_top=_gp[0]+_gp[2]+1;
_left=_gp[1];
if(_m[_mnu][11]=="rtl"){_left=_left-(_mp[3]-_gp[3])-_mi[_i][27]}if(_m[_mi[_i][0]][5]=="bottom"){_top=(_gp[0]-_mp[2])}}else{_top=_gp[0]+_subOffsetTop;
_left=_gp[1]+_gp[3]+_subOffsetLeft;
if(_m[_mnu][11]=="rtl"){_left=_gp[1]-_mp[3]-_subOffsetLeft}}if(_left<0)_left=0;
if(_top<0)_top=0;
if(_m[_mnu][2]){if(isNaN(_m[_mnu][2])&&_m[_mnu][2].indexOf("offset=")==0){_os=_m[_mnu][2].substr(7,99);
_top=_top+parseInt(_os)}else{_top=_m[_mnu][2]}}if(_m[_mnu][3]){if(isNaN(_m[_mnu][3])&&_m[_mnu][3].indexOf("offset=")==0){_os=_m[_mnu][3].substr(7,99);
_left=_left+parseInt(_os)}else{_left=_m[_mnu][3]}}if(_left+_mp[3]>_bW+_sL){if(!horiz&&(_gp[1]-_mp[3])>0){_left=_gp[1]-_mp[3]-_subOffsetLeft}else{_left=(_bW-_mp[3])-2}}if(!horiz&&_top+_mp[2]>_bH+_sT){_top=(_bH-_mp[2])-2}if(!horiz){_top=_top-_m[_mnu][6][65]}else{_top--;
_left--}spos(_mnO,_top+_m[_mnu][6][65],_left+_m[_mnu][6][65]);
if(_m[_mnu][5])_setPosition(_mnu);
_zi++;
_mnb=gmobj("bord"+_mnu);
_oMT=setTimeout("menuDisplay("+_mnu+",1)",_menuOpenDelay);
if(_sm[_sm.length-1]!=_mnu)_sm[_sm.length]=_mnu}}_setPath(_iP)
}

function _setPosition(_mnu)
{if(_m[_mnu][5]){_gm=gmobj("menu"+_mnu);
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
if(_gm.topOffset)_tp=_tp+parseInt(_gm.topOffset)}if(_lft<0)_lft=0;
spos(_gm,_tp,_lft);
if(_m[_mnu][19])_m[_mnu][19]=_tp;
if(_tp)_tp=_tp-_m[_mnu][6][65];
if(_lft)_lft=_lft-_m[_mnu][6][65];
_sb=gmobj("bord"+_mnu);
spos(_sb,_tp,_lft);
_gm._tp=_tp}
}

function _MScan()
{_bW=self.innerWidth-16;
_bH=self.innerHeight-17;
_sT=self.pageYOffset;
if(_startM==1){for(_a=0;
_a<_m.length;
_a++){if(_m[_a][5]){_setPosition(_a)}}}else{if((_bH!=_oldbH)&&(_bW!=_oldbW)){location.reload()}}_startM=0;
_oldbH=_bH;
_oldbW=_bW}setInterval("_MScan()",200);

function getMouseXY(e)
{
	MouseX=e.pageX;
	MouseY=e.pageY
}

_d.captureEvents(Event.MOUSEMOVE);
_d.onmousemove=getMouseXY;
