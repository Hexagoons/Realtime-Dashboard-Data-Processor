var fs = require('fs');

var buffers = [];
var string = "";
var timeBlocks = "";

var readStream = fs.createReadStream('./bin/stationdata.bin');
readStream
	.on('data', function (chunk) {
		buffers.push(chunk);
	})
	.on('end', function () {
		var buffer = Buffer.concat(buffers);

		var index = 0;
		var current = "";
		while(index < buffer.length) {
			var date = buffer.slice(index+4, index+14).toString('ascii');
			var time = buffer.slice(index+14, index+22).toString('ascii');
			var dateTime = date + ' ' + time;

			if(dateTime !== current) {
				current = dateTime;
				timeBlocks += (dateTime + '\n');
			}

			string += (dateTime + '\n');

			index += 63;
		}

		fs.appendFile('timestamps.txt', string, function (err) {});
		fs.appendFile('time-blocks.txt', timeBlocks, function (err) {});
	});
