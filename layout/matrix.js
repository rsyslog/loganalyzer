//Matrix Script
var density=0.2;
var colors=new Array('#030','#060','#393','#6C6','#fff');
var minL=5; var maxL=30;
var minS=30; var maxS=80;
var cW=11; var cH=11;
var ascSt=33; var ascEnd=126;
var DOM=(document.getElementById)?true:false;
var scrW,scrH;
var cl=new Array;

function init() 
{
	scrW=document.body.clientWidth-25-cW;
	scrH=document.body.clientHeight;
	if (!scrW) 
	{
		scrW = 750; 
		scrH = 550;
	}
	for (var clN=0;clN<Math.round(scrW/cW*density);clN++) 
	{
		cl[cl.length]=[getX(),Math.round(Math.random()*scrH/cH),Math.round(Math.random()*(maxL-minL))+minL,getS()];
		document.write('<div id="c'+clN+'" style="position:absolute;z-index=1;font-family: Tahoma, Verdana, Arial, Helvetica;font-size: 8pt;width:'+cW+'px;left:'+(cl[clN][0]*cW)+'px">');
		for (var cN=0;cN<=cl[clN][2];cN++) document.write('<span style="color:'+colors[Math.round(cN/cl[clN][2]*(colors.length-1))]+'">'+String.fromCharCode(Math.round(Math.random()*(ascEnd-ascSt)+ascSt))+'</span><br>');
		document.write('</div>');
		cl[clN][5]=document.getElementById('c'+clN).style;
		cl[clN][4]=setI(clN);
	}
}

function move(n) {
cl[n][1]++;
if (Math.round(scrH/cH)+cl[n][2]<cl[n][1]) {
clearInterval(cl[n][4]);
cl[n][0]=getX();
cl[n][1]=-cl[n][2]-1;
cl[n][3]=getS();
cl[n][5].left=cl[n][0]*cW;
cl[n][4]=setI(n);
} else cl[n][5].top=(cl[n][1]-cl[n][2])*cH;
}

function getS() {return Math.round(Math.random()*(maxS-minS))+minS}
function getX() {return Math.round(Math.random()*scrW/cW)}
function setI(n) {return setInterval('move('+n+')',Math.round(3000/cl[n][3]))}

init();
