/*
Javscript menu
Misc stuff
*/

_mD=2;
_d=document;
_n=navigator;
_nv=$tL(_n.appVersion);
_nu=$tL(_n.userAgent);
_ps=parseInt(_n.productSub);
_f=false;
_t=true;
_n=null;
_wp=window.createPopup;
ie=(_d.all)?_t:_f;
ie4=(!_d.getElementById&&ie)?_t:_f;
ie5=(!ie4&&ie&&!_wp)?_t:_f;
ie55=(!ie4&&ie&&_wp)?_t:_f;
ns6=(_nu.indexOf("gecko")!=-1)?_t:_f;
konq=(_nu.indexOf("konqueror")!=-1)?_t:_f;
sfri=(_nu.indexOf("safari")!=-1)?_t:_f;
if(konq||sfri){_ps=0;
ns6=0}ns4=(_d.layers)?_t:_f;
ns61=(_ps>=20010726)?_t:_f;
ns7=(_ps>=20020823)?_t:_f;
op=(window.opera)?_t:_f;
op5=(_nu.indexOf("opera 5")!=-1)?_t:_f;
op6=(_nu.indexOf("opera 6")!=-1)?_t:_f;
op7=(_nu.indexOf("opera 7")!=-1||_nu.indexOf("opera/7")!=-1)?_t:_f;
mac=(_nv.indexOf("mac")!=-1)?_t:_f;
mac45=(_nv.indexOf("msie 4.5")!=-1)?_t:_f;
mac50=(mac&&_nv.indexOf("msie 5.0")!=-1)?_t:_f;
if(ns6||ns4||op||sfri)mac=_f;
ns60=_f;
if(ns6&&!ns61)ns60=_t;
IEDtD=0;
if(!op&&(_d.all&&_d.compatMode=="CSS1Compat")||(mac&&_d.doctype&&_d.doctype.name.indexOf(".dtd")!=-1))IEDtD=1;
if(op7)op=_f;
if(op)ie55=_f;
_st=0;
_en=0;
$=" ";
_m=new Array();
_mi=new Array();
_sm=new Array();
_tsm=new Array();
_cip=new Array();
_mn=-1;
_el=0;
_ael=0;
_Bel=0;
_bl=0;
_Omenu=0;
_MT=setTimeout("",0);
_oMT=setTimeout("",0);
_cMT=setTimeout("",0);
_scrmt=setTimeout("",0);
_mst=setTimeout("",0);
_zi=999;
_c=1;
_mt="";
_oldel=-1;
_sH=0;
_sW=0;
_bH=500;
_oldbH=0;
_bW=0;
_oldbW=0;
_cD=0;
_ofMT=0;
_startM=1;
_sT=0;
_sL=0;
_mcnt=0;
_mnuD=0;
_itemRef=-1;
inopenmode=0;

_$S={menu:0,text:1,url:2,showmenu:3,status:4,onbgcolor:5,oncolor:6,offbgcolor:7,offcolor:8,offborder:9,separatorcolor:10,padding:11,fontsize:12,fontstyle:13,fontweight:14,fontfamily:15,high3dcolor:16,low3dcolor:17,pagecolor:18,pagebgcolor:19,headercolor:20,headerbgcolor:21,subimagepadding:22,subimageposition:23,subimage:24,onborder:25,ondecoration:26,separatorsize:27,itemheight:28,image:29,imageposition:30,imagealign:31,overimage:32,decoration:33,type:34,target:35,align:36,imageheight:37,imagewidth:38,openonclick:39,closeonclick:40,keepalive:41,onfunction:42,offfunction:43,onbold:44,onitalic:45,bgimage:46,overbgimage:47,onsubimage:48,separatorheight:49,separatorwidth:50,separatorpadding:51,separatoralign:52,onclass:53,offclass:54,itemwidth:55,pageimage:56,targetfeatures:57,imagealt:58,pointer:59,imagepadding:60,valign:61,clickfunction:62,bordercolor:63,borderstyle:64,borderwidth:65,overfilter:66,outfilter:67,margin:68,pagebgimage:69,swap3d:70,separatorimage:71,pageclass:72,menubgimage:73};
$So="";
_$M={items:0,name:1,top:2,left:3,itemwidth:4,screenposition:5,style:6,alwaysvisible:7,align:8,orientation:9,keepalive:10,openstyle:11,margin:12,overflow:13,position:14,overfilter:15,outfilter:16,menuwidth:17,itemheight:18,followscroll:19,menualign:20,mm_callItem:21,mm_obj_ref:22};
_pru="";
_c=0;
menuname.prototype.SbMnu=ami;
menuname.prototype.insertItem=_iI;

function M_hideLayer(){}
function opentree(){}

function chop(_ar,_pos)
{
	var _tar=new Array();
	for(_a=0;_a<_ar.length;_a++)
	{
		if(_a!=_pos)
		{
			_tar[_tar.length]=_ar[_a];
		}
	}
	return _tar;
}

function remove(_ar,_dta)
{
	var _tar=new Array();
	for(_a=0;_a<_ar.length;_a++)
	{
		if(_ar[_a]!=_dta)
			{_tar[_tar.length]=_ar[_a]}
	}
	
	return _tar;
}

function copyOf(_w){for(_cO in _w)
{
	this[_cO]=_w[_cO]}
}

function $tL($S)
{
	return $S.toLowerCase();
}

function MakeMenus()
{
	for(_a=_mcnt;_a<_m.length;_a++)
		{_drawMenu(_a,1)}
}

function MenuStyle()
{
	for($i in _$S)
		this[$i]=_n;
}

function menuname(name)
{
	for($i in _$M)
		this[$i]=_n;
	
	this.name=$tL(name);_c=1;
	_mn++;
	this.menunumber=_mn;
}

function _incItem(_it)
{
	_mi[_bl]=new Array();
	
	for($i in _x[6])
		_mi[_bl][_$S[$i]]=_x[6][$i];
	
	_mi[_bl][0]=_mn;
	_it=_it.split(";");

	for(_a=0;_a<_it.length;_a++)
	{
		_sp=_it[_a].indexOf("`");
		if(_sp!=-1)
		{
			_tI=_it[_a];
			for(_b=_a;_b<_it.length;_b++)
			{
				_tI+=";"+_it[_b+1];
				_a++;
				if(_it[_b+1].indexOf("`")!=-1)
					_b=_it.length
			}
			_it[_a]=_tI.replace(/`/g,"")
		}
		_sp=_it[_a].indexOf("=");
		if(_sp==-1)
		{
			if(_it[_a])
				_si=_si+";"+_it[_a];
		}
		else
		{
			_si=_it[_a].slice(_sp+1);
			_w=_it[_a].slice(0,_sp);
			if(_w=="showmenu")
				_si=$tL(_si)
		}
		
		if(_it[_a])
		{
			_mi[_bl][_$S[_w]]=_si;
		}
	}
	
	_m[_mn][0][_c-2]=_bl;
	_c++;
	_bl++;
	_mil=1;
	
	if(_m[_mn][7]&&_c==3)
	{
		$c=0;
		for($i in _$S)
		{
			if($c==2)
				$T2=";"+$i;
			if($c==1)
				$T1=$i+"=";
			$c++
		}
		$1=eval("$tL(String.fromCharCode(95,80,82,85))");
		$2=eval($1).split($);
	}
	_mil=2;
}

function _iI(txt,_pos)
{
	_oStyle=_m[_mn][6];
	_m[_mn][6]=this.style;
	this.SbMnu(txt);
	_mil=_mi.length;
	_M=_m[this.menunumber];
	_nmi=new Array();
	if(_pos>=_M[0].length)_pos=_M[0].length;
	if(!_M[0][_pos])_M[0][_pos]=_M[0][_M[0].length-1]+1;
	_inum=_M[0][_pos];
	_cnt=0;
	for(_a=0;_a<_mil;_a++)
	{
		if(_inum==_a)
		{
			_nmi[_cnt]=_mi[_mi.length-1];
			_nmi[_cnt][0]=this.menunumber;
			_M[0][_M[0].length]=_cnt;
			_cnt++
		}
		_nmi[_cnt]=_mi[_a];
		_cnt++
	}

	_mi=_nmi;
	_tpos=0;
	_omnu=-1;
	
	for(_a=0;_a<_mil;_a++)
	{
		_mnu=_mi[_a][0];
		if(_mnu!=_omnu)
		{
			_m[_mnu][0]=new Array();
			_tpos=0;
		}
		_m[_mnu][0][_tpos]=_a;
		_tpos++;
		_omnu=_mnu;
	}
	_m[_mn][6]=_oStyle;
}
	
function ami(txt)
{
	_t=this;
	if(_c==1)
	{
		_c++;
		_m[_mn]=new Array();
		_x=_m[_mn];
		for($i in _t)
			_x[_$M[$i]]=_t[$i];
		
		_x[21]=-1;
		_x[0]=new Array();
		if(!_x[12])_x[12]=0;
		_MS=_m[_mn][6];
		_MN=_m[_mn];
		if(!_MN[15])
			_MN[15]=_MS.overfilter;
		if(!_MN[16])
			_MN[16]=_MS.outfilter;
		if(!_MN[12])
			_MN[12]=_MS.margin;
		if(!_MS[65])
			_MS[65]=0;
	}
	_incItem(txt);
}