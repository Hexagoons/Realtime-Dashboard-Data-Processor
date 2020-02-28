var reader = require('./reader');

module.exports.read = function (stn, callback) {
	reader.init(function () {

		var minutes = 5;
		var now = new Date();
		var seconds = now.getSeconds() - 6;
		if(seconds < 0) {
			minutes = 4;
			seconds = 60 + seconds;
		}
		var currentDate = new Date(2020, 0, 30, 19, minutes, seconds);
		currentDate.setMilliseconds(0);

		// var currentDate = new Date();
		// currentDate.setSeconds(currentDate.getSeconds() - 1);
		// currentDate.setMilliseconds(0);

		reader.readMeasurementBlock(currentDate, function (buffer) {
			var index = reader.findMeasurementInBlock(stn, buffer);

			if (index === null) {
				console.log("NOT FOUND");
				return;
			}

			callback(
				reader.readMeasurement(buffer, index)
			);
		});
	});
};

// Main function DEBUG
if (require.main === module) {
	module.exports.read(729090, function (data) {
		process.stdout.write(JSON.stringify(data));
	});
}