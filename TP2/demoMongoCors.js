var express = require("express");
var cors = require("cors");                 // Pour installer : npm install cors
var bodyParser = require('body-parser');    // Pour installer : npm install body-parser
var auth = require("basic-auth");
var mongoClient = require('mongodb').MongoClient;
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

app.route("/poussin")
    .get(function(req, res, next){
        mongoClient.connect(url, function(err, db){
			if(!err)
			{
				db = db.db("mesPoussins");
				db.collection("poussins").find().toArray(function(err, documents)
				{
					if(!err)
					{
						for(var i = 0; i < documents.length, i++)
						{
							documents[i] = "allo " + i;
						}
						res.json(documents);
					}
				});
				db.close();
			}
		});
		//res.send("GET");
    })
    .put(baseAuth, function(req, res, next){
        res.json({allo:"test"});
        //res.send("PUT");
    });

app.route("/poussin/:id/note")
    .get(function(req, res, next){
        res.send("GET : id = " + req.params.id);
    })
    .put(baseAuth, function(req, res, next){
        res.json({allo:"test"});
        //res.send("PUT");
    });


app.all("*", function(req, res){
    res.status(400).send();
});

app.listen(8080, function(){
    console.log("C'est partie!!!!");
})