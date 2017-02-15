
var express = require('express');

// app parce que c'est toujours comme sa que les demo l'appel
var app = express(); 

var baseAuth = function(req, res, next)
{
	var reponse = true;
	if(reponse)
	{
		next();
	}
	else
	{
		res.status(401).send();
	}
}

app.route("/poussin", function(req, res, next){
	console.log(req);
	//res.send('bien recu');
	.get(function(req, res, next){
		res.send('GET');
	})
	.put(baseAuth, function(req, res, next){
		res.send('PUT');
	})
});

app.all('*', function (req, res, next){
	res.status(400).send();
});

app.listen(8080, function(){
	console.log("c'est partie!!!!");
});
