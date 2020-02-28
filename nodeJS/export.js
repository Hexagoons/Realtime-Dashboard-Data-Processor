var reader = require('./reader');

function read(stn, startDate, endDate, measurements, callback) {
	reader.readMeasurementBlock(startDate,function (buffer) {
		var index = reader.findMeasurementInBlock(stn, buffer);

		if(index === null) {
			console.log("NOT FOUND");
			return;
		}

		measurements.push(reader.readMeasurement(buffer, index));

		startDate.setSeconds(startDate.getSeconds() + 1);
		if(startDate.getTime() !== endDate.getTime()) {
			read(stn, startDate, endDate, measurements, callback);
		} else {
			callback(measurements);
		}
	});
}

module.exports.read = function (stn, startDate, endDate, callback) {
	reader.init(function () {
		endDate.setSeconds(endDate.getSeconds() + 1);

		read(stn, startDate, endDate, [], function (measurements) {
			callback(measurements);
		});
	});
};

// Main function DEBUG
if (require.main === module) {
	if(process.argv.length < 7) {
		process.stdout.write('usage: node export.js stn from from-time end end-time');
		process.exit(1);
	}

	var stn = parseInt(process.argv[2]);
	var startAt = new Date(process.argv[3] + ' ' + process.argv[4]);
	startAt = new Date(2020, 0,30, 19, 4, 54); // DEBUG
	startAt.setMilliseconds(0);
	var endAt = new Date(process.argv[5] + ' ' + process.argv[6]);
	endAt = new Date(2020, 0,30, 19, 5, 54);  // DEBUG
	endAt.setMilliseconds(0);

	module.exports.read(stn, startAt, endAt, function (data) {
		process.stdout.write(JSON.stringify(data));
		process.exit(0);
	});
}