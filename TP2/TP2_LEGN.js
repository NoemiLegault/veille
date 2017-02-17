var express = require("express");
var cors = require("cors");                 // Pour installer : npm install cors
var bodyParser = require('body-parser');    // Pour installer : npm install body-parser
var auth = require("basic-auth");
var mongo = require('mongodb');
var mongoClient = mongo.MongoClient;
var url = "mongodb://localhost:27017";

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

app.use(cors());

app.use(bodyParser.json());

app.put("*", baseAuth);
app.post("*", baseAuth);
app.delete("*", baseAuth);

app.route("/biere")
    .get(function(req, res, next)
	{
        mongoClient.connect(url, function(err, db)
		{
			if(!err)
			{
				db = db.db("Bieres");
				
				db.collection("biere").find().toArray(function(err, documents){
					if(!err)
					{
						res.json(documents);
					}
				});
				db.close();
			}
		});
    })
	
    .put(function(req, res, next)
	{
		mongoClient.connect(url, function(err, db)
		{	
			if(!err)
			{
				db = db.db("Bieres");
				var params = req.body;
				var maCollection = db.collection("biere");
				maCollection.insertOne(params, function(err, docs)
				{
					if(!err)
					{
						res.json(docs);
					}
				});
					
				db.close();
			}	
		});
    });
	

app.route("/biere/:id")
    .get(function(req, res, next)
	{
        //res.send("GET : id = " + req.params.id);	
		mongoClient.connect(url, function(err, db)
		{
			if(!err)
			{
				db = db.db("Bieres");
				var monId = req.params.id;
				if(db.collection("biere"))
				{
					var maCollection = db.collection("biere");
					maCollection.findOne( {'_id': mongo.ObjectId(monId)}, function(err, docs)
					{
						if(!err)
						{
							res.send(docs);
						}
					});
				}
			}
			db.close();
		});
					
    })
	
	.post(function(req, res, next)
	{
		mongoClient.connect(url, function(err, db)
		{
			if(!err)
			{
				db = db.db("Bieres");
				var monId = req.params.id;
				var params = req.body;
				console.log(params);
				var maCollection = db.collection("biere");
				maCollection.updateOne({'_id': mongo.ObjectId(monId)}, {$set: params}, function(err, docs)
				{
					if(!err)
					{
						res.send(docs);
					}
				});
					
				db.close();
			}
		});
    })
	
	.delete(function(req, res, next)
	{
		mongoClient.connect(url, function(err, db)
		{	
			if(!err)
			{
				db = db.db("Bieres");
				var monId = req.params.id
				var maCollection = db.collection("biere");
				maCollection.remove({'_id': mongo.ObjectId(monId)}, function(err, docs)
				{
					if(!err)
					{
						res.send(docs);
					}
				});
					
				db.close();
			}
		});
    });
	
app.route("/biere/:id/note")
	.get(function(req, res, next)
	{
		mongoClient.connect(url, function(err, db)
		{
			if(!err)
			{
				db = db.db("Bieres");
				var monId = req.params.id;
				if(db.collection("biere"))
				{
					var maCollection = db.collection("biere");
					maCollection.findOne( {'_id': mongo.ObjectId(monId)}, function(err, docs)
					{
						//console.log(docs.nom);
						
						if(!err)
						{
							//console.log(docs.notes);
							if(docs.notes && docs.notes.length > 0)
							{
								var note = 0;
								for(var x=0;x<docs.notes.length;x++)
								{
									note += parseInt(docs.notes[x].note);
									//console.log(docs.notes[x].note);
								}
								note = (note/parseInt(docs.notes.length));
								res.send('La moyenne des notes pour la bière ' + docs.nom + 'est : ' + note + ' sur ' + docs.notes.length + ' participant');
							}
							else if(docs.nom)
							{
								res.send('aucune note pour la bière ' + docs.nom);
							}
							else
							{
								res.send('cette bière n\'existe pas');
							}
						}
					});
				}
			}
			db.close();
		});			
    })
	
	.put(function(req, res, next)
	{
		mongoClient.connect(url, function(err, db)
		{
			if(!err)
			{
				db = db.db("Bieres");
				var monId = req.params.id;
				if(db.collection("biere"))
				{
					var maCollection = db.collection("biere");
					maCollection.createIndex({'nodes.courriel': req.body.courriel},{unique:true}, function(err, docs)
					{
						console.log(docs);
					});
					maCollection.updateOne({'_id': mongo.ObjectId(monId)}, {$pull: {'nodes.courriel': req.body.courriel}}, function(err, docs){});
					
					maCollection.updateOne({'_id': mongo.ObjectId(monId)}, {$push: {'notes':{'courriel': req.body.courriel,'note': req.body.note}}}, {upsert:true}, function(err, docs)
					{
						
						if(!err)
						{
							console.log('yess!');
							res.send('test');
						}
					});
					
					
				}
			}
			db.close();
		});
					
    });

app.route("/biere/:id/commentaire")
	.get(function(req, res, next)
	{
		mongoClient.connect(url, function(err, db)
		{
			if(!err)
			{
				db = db.db("Bieres");
				var monId = req.params.id;
				if(db.collection("biere"))
				{
					var maCollection = db.collection("biere");
					maCollection.findOne( {'_id': mongo.ObjectId(monId)}, function(err, docs)
					{		
						if(!err)
						{
							if(docs.commentaires && docs.commentaires.length > 0)
							{
								var com = '';
								for(var x=0;x<docs.commentaires.length;x++)
								{
									com += docs.commentaires[x].commentaire + '\n\r, ';
								}
								res.send('Les commentaire pour la bière ' + docs.nom + 'sont : \n\r' + com);
							}
							else if(docs.nom)
							{
								res.send('aucun commentaire pour la bière ' + docs.nom);
							}
							else
							{
								res.send('cette bière n\'existe pas');
							}
						}
					});
				}
			}
			db.close();
		});			
    })
	
	.put(function(req, res, next)
	{
		mongoClient.connect(url, function(err, db)
		{
			if(!err)
			{
				db = db.db("Bieres");
				var monId = req.params.id;
				if(db.collection("biere"))
				{
					var maCollection = db.collection("biere");
					maCollection.updateOne({'_id': mongo.ObjectId(monId)}, {$push: {'commentaires':{'courriel': req.body.courriel,'commentaire': req.body.commentaire}}}, function(err, docs)
					{
						
						if(!err)
						{
							res.send('commentaire ajouter');
						}
					});
					
					
				}
			}
			db.close();
		});
					
    });

	


app.all("*", function(req, res, next){
    res.status(400).send();
});

app.listen(8080, function(){
    console.log("Cest partie!!!!");
});

	
	