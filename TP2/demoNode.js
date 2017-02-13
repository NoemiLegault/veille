
var express = require('express');
var app = express(); // app parce que c'est toujours comme sa que les demo l'appel
var contenue = "";

app.listen(8080, function () {
  console.log('test app listening on port 8080!!!!');
});

app.get("/", function(req, res, next){
	console.log(req);
	//res.send('bien recu');
	contenue += ('bien recu ');
	next();
});

app.get('/poussin/', function(req, res, next){
	console.log(req);
	//res.send('bien recu en get');
	contenue += ('bien recu en get');
	res.send(contenue);
});

app.post('/poussin/', function(req, res){
	console.log(req);
	res.send('bien recu en post');
});

app.put('/poussin/', function(req, res){
	console.log(req);
	res.send('bien recu en put');
});

app.delete('/poussin/', function(req, res){
	console.log(req);
	res.send('bien recu en delete');
});



// toute les routes 'non functionnel' -> qui n'on pas été déterminer plus haut. 
app.all('*', function(req, res){ 
	res.status(400).send('mauvaise réponse');
});


