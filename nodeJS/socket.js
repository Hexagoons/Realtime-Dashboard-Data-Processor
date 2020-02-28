var server = require('http').Server();

var io = require('socket.io')(server);

var mysql = require('mysql');

var shajs = require('sha.js');

var config = require('./config');
var mostRecent = require('./mostRecent');

var con = mysql.createConnection(config);

io.use(function(socket, next){
	con.connect(function(err) {
		if (err) {next(new Error('Authentication error'));}

		con.query("SELECT id, first_name, last_name, role FROM users WHERE id = ?", [socket.handshake.query.id], function (err, result, fields) {
			if (err) {next(new Error('Authentication error'));}
			if (result.length !== 1) {
				next(new Error('Authentication error'));
			} else {
				var hash = shajs('sha256').update(result[0].id + result[0].first_name + ' ' + result[0].last_name + result[0].role).digest('hex');
				if(hash === socket.handshake.query.token) {
					next();
				} else {
					next(new Error('Authentication error'));
				}
			}
		});
	});
});
io.on('connection', (socket) => {
	socket.on('data-request', function (stn) {
		mostRecent.read(parseInt(stn), function (data) {
			io.sockets.connected[socket.id].emit('data-update', {
				'chart1': data['wdsp'],
				'chart2': data['temp'],
				'chart3': data['sndp'],
				'chart4': data['tornado'],
				'chart5': data['snow'],
				'chart6': data['ice']
			});
		});
	});
});

server.listen(3000, function () {
	console.log('started on localhost:3000');
});
