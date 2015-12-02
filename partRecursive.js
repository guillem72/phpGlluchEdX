var page = require('webpage').create();
var fs=require("fs");

url="https://www.edx.org/course/android-introduccion-la-programacion-upvalenciax-aip201x";

var filename="electronic-links.txt";
var filename="computer-links.txt";
var filename="dataAnalysis-links.txt";

var target="courses0/";

var links0 = fs.read(filename);

var links=links0.split("\n");//break by lines
//console.log(links);

course(links);

//course(url);
function course(lista){
if(lista.length<=0)
{
	console.log("All pages downloaded. Have a good day");
	phantom.exit();
	return true;
}
else
{
	if (lista[0]!="")
	{
		console.log("Working on "+lista[0]);
		
		page.open(encodeURI(lista[0]), function(status) 
		{  
			fs.write(target+page.title+'.html', page.content,'w');
			console.log(page.title+" done");
			sleepFor(60);
			lista.shift();
			course(lista);
		 
		});
	}
	else
	{
		lista.shift();
		course(lista);
	}
	
	//console.log(lista);
}

/*
 *
page.open(encodeURI(url), function(status) {  
    console.log(page.title);
	
	fs.write(target+page.title+'.html', page.content,'w');
	page.viewportSize = {  width: 960 };
	//page.render('course.png');
 
});
*/
}

function sleepFor( sleepDuration ){
    var now = new Date().getTime();
    while(new Date().getTime() < now + sleepDuration){ /* do nothing */ } 
}

