
var canvas;
var padding = 25;
var saturation = 150;
var ringColor = '#777';
var bkgdColor = '#EEE';
var width = 2;
var widthVar = 50;
var life = 60;
var lifeVar = 50;
var energy = 1;
var energyVar =  50;
var rndmColor = true;
var runner;

function init()
{
	canvas = new JS3('cnvs');
	canvas.background = bkgdColor;
	canvas.interactive = true;	
	canvas.run(fadeRing); 
	canvas.click = function(e){ drawRing(e.x, e.y) };	
	runner = canvas.run(drawRing, 10/saturation); drawRing();	
}

function drawRing(xx, yy)
{
	var x = xx || JS3.getRandomValue(padding, canvas.width-padding);
	var y = yy || JS3.getRandomValue(padding, canvas.height-padding);	
	var k = new JS3Circle({fill:false, x:x, y:y, size:1});
		k.x1 = k.x; k.y1 = k.y;
		k.strokeColor = rndmColor ? JS3.getRandomColor() : ringColor;
	var ev = (energyVar/100)*energy;
		k.grow = JS3.getRandomValue(energy - ev, energy + ev);
	var lv = (lifeVar/100)*life;
		k.maxSize = JS3.getRandomValue(life - lv, life + lv);
	var wv = (widthVar/100)*width;		
		k.strokeWidth = JS3.getRandomValue(width - wv, width + wv);
	canvas.addChild(k);
}

function fadeRing()
{
	for (var i = canvas.numChildren - 1; i >= 0; i--){
		var k = canvas.getChildAt(i);
		k.size+=k.grow;
		k.x = k.x1 - k.size/2;
		k.y = k.y1 - k.size/2;
		if (k.size > k.maxSize){
			k.alpha -= .1;
			if (k.alpha < 0) canvas.removeChildAt(i);
		}
	};
}

init();
