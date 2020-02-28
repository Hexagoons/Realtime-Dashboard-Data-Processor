var fs = require('fs');
var config = require('./config');

var measurementsSize = 63;
var firstMeasurement = null;

module.exports.init = function(callback) {
	if(firstMeasurement !== null) {
		callback();
		return;
	}

	var readStream = fs.createReadStream(config['stationdata'], { start : 4, end: 22 });
	readStream
		.on('data', function (chunk) {
			var date = chunk.slice(0, 10).toString('ascii');
			var time = chunk.slice(10, 18).toString('ascii');

			firstMeasurement = new Date(date + ' ' + time);
			callback();
		});
};

function readMeasurementsFromFile(start, end, callback) {
	var buffers = [];
	var readStream = fs.createReadStream(config['stationdata'], { start : start, end: end });
	readStream
		.on('data', function (chunk) {
			buffers.push(chunk);
		})
		.on('end', function () {
			callback(Buffer.concat(buffers));
		});
}

module.exports.readMeasurementBlock = function(searchedForDate, callback) {
	var difference = (searchedForDate - firstMeasurement) / 1000;
	var measurementsPerSecond = config['measurementsPerSecond'];

	var start = measurementsPerSecond * measurementsSize * difference;
	var end = start + (measurementsPerSecond * measurementsSize);
	readMeasurementsFromFile(start, end, callback);
};

module.exports.findMeasurementInBlock = function(stn, buffer) {
	var index = 0;

	while(true) {
		if(index+4 > buffer.length) {
			return null;
		}

		var currentStn = buffer.readUIntBE(index, 4);

		if(currentStn === stn) {
			break;
		}

		index += measurementsSize;
	}

	return index;
};

module.exports.readMeasurement = function (buffer, index) {
	var measurement = {};

	measurement['stn'] = buffer.readUIntBE(index, 4);
	measurement['temp'] = (buffer.readFloatBE(index+22)).toFixed(2);
	measurement['date'] = buffer.slice(index+4, index+14).toString('ascii');
	measurement['time'] = buffer.slice(index+14, index+22).toString('ascii');
	measurement['wdsp'] = (buffer.readFloatBE(index+42)).toFixed(2);
	measurement['sndp'] = (buffer.readFloatBE(index+50)).toFixed(2);

	var events = buffer.slice(index+54, index+55)[0];
	measurement['ice'] = (events & parseInt('10000000', 2)) >> 7;
	measurement['snow'] = events & parseInt('00100000', 2) >> 5;
	measurement['tornado'] = events & parseInt('00000001', 2);

	return measurement;
};

module.exports.getMeasurementSize = function () {
	return measurementsSize;
};