var fs = require("fs");
var sprintf = require("sprintf-js").sprintf;
var uuid = require("node-uuid");
var gearman = require("abraxas");
var mysql = require("mysql");

// Create MySql adapter
var mysqlAdapter = mysql.createConnection({
	host: "localhost",
	database: "jobs_currencyfair_endpoint",
	user: "currencyfair_api",
	password: "WER7rmYLZY7RWpvF"
});

// Create main processor server and socket and prepare Gearman server
var processorServer = require("https").createServer({
	key: fs.readFileSync("certs/key.pem"),
	cert: fs.readFileSync("certs/cert.pem")
}).listen(5667);
var processorSocket = require("socket.io").listen(processorServer);
var processorGearman = gearman.Client.connect();

// Create notification server and socket
var notificationServer = require("http").createServer().listen(5668);
var notificationSocket = require("socket.io").listen(notificationServer);

var newMessages = [];

// Batch messages and send notifications every second to prevent stuffing socket connection is we have high load
var intervalId = setInterval(function() {
	var messagesCount = newMessages.length;
	
	if (messagesCount)
	{
		var messageIds = newMessages.splice(0, messagesCount);
		
		notificationSocket.emit("updateReports", {messageIds: messageIds});
	}
}, 1000);

processorSocket.on("connection", function(socket) {
	// Let's process message that we receive from our endpoint
	socket.on("processMessage", function(data) {
		// Only process message if it came from trusted source
		if (data.token && data.token === "x6bQ78k6d63B1678Ncj8rUoT0z5Tv0Gv")
		{
			// Proxy data to our Gearman backgroud job so that we can be ready for the next message
			processorGearman.submitJobBg(
				"processMessageJob",
				{
					uniqueid: sprintf("%s::%s", "currencyfair-processor", uuid.v4())
				},
				JSON.stringify(data.message),
				function(error) {
					if (error) 
					{
						console.log("Error (currencyfair-processor):");
						console.error(error);					
					}
				}
			);
		}
	});
});

// Processing each message
processorGearman.registerWorker("processMessageJob", function(task) {
	var data = JSON.parse(task.payload);

	// Prepare message's ID which we're going to send to the dashboard
	newMessages.push(data.id);
	
	// Generate some dummy report so we can see how everything works as a system
	mysqlAdapter.query(
		"INSERT INTO reports_by_country (`date`, country_code, count_messages) VALUES(DATE(?), ?, 1) ON DUPLICATE KEY UPDATE count_messages = count_messages + VALUES(count_messages)", 
		[data.datetime_created, data.country_code], 
		function(error) {
			
		}
	);

	task.end();
});