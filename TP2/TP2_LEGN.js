var express = require("express");
var cors = require("cors");                 // Pour installer : npm install cors
var bodyParser = require('body-parser');    // Pour installer : npm install body-parser
var auth = require("basic-auth");
var mongo = require('mongodb')
var mongoClient = mongo.MongoClient;
var url = "mongodb://localhost:27017";

var app = express();


var baseAuth = function(req, res, next)
{
    var reponse = true;
    //res.json(req);
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
						/*
						for(var i = 0; i < documents.length, i++)
						{
							documents[i] = "allo " + i;
						}
						*/
						res.json(documents);
					}
				});
				db.close();
			}
		});
		//res.send("GET");
    })
	
    .put(function(req, res, next)
	{
		//res.send(req.params.id);
		//console.log(req.params.id);
		res.json(req.params[0]);
		//console.log(req.params[1]);
		mongoClient.connect(url, function(err, db)
		{
			
			if(!err)
			{
				db = db.db("Bieres");
				if(db.collection("biere"))
				{
					var params = req.params;
					var maCollection = db.collection("biere");
					//maCollection.insertOne( {'test': 'testfgseurfg', 'id':2}, function(err, docs)
					maCollection.insertOne(params, function(err, docs)
					{
						if(!err)
						{
							res.json(docs);
						}
					});
				}
				db.close();
			}
			
		})
        //res.json({allo:"test"});
        //res.send("PUT");
    })
	

app.route("/biere/:id")
    .get(function(req, res, next)
	{
		var monId = req.params.id
        //res.send("GET : id = " + req.params.id);
			
		mongoClient.connect(url, function(err, db)
		{
			if(!err)
			{
				db = db.db("Bieres");
				if(db.collection("biere"))
				{
					var maCollection = db.collection("biere");
					//maCollection.findOne( {},{fields:{'_id': monId}}, function(err, docs)
					maCollection.findOne( {},{fields:{'id': monId}}, function(err, docs)
					{
						if(!err)
						{
							res.json(docs);
						}
					});
				}
			}
			db.close();
		});
			
		//res.send(id);			
    })
	/*
    .delete(baseAuth, function(req, res, next)
	{
		res.json(req.params);
        //res.json({allo:"test"});
        //res.send("PUT");
    });
	*/


app.all("*", function(req, res){
    res.status(400).send();
});

app.listen(8080, function(){
    console.log("C'est partie!!!!");
})