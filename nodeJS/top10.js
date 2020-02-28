var mysql = require('mysql');

var reader = require('./reader');
var config = require('./config');
var con = mysql.createConnection(config);

var offsets = {
	'temp': 22,
	'wdsp': 42,
	'sndp': 50
};

function arrayColumn(array, columnName) {
	return array.map(function(value,index) {
		return value[columnName];
	})
}

module.exports.read = function (category, callback) {
	reader.init(function () {
		con.query("SELECT stn FROM stations WHERE latitude >= 60", function (err, result) {
			var availableStns = arrayColumn(result, 'stn');

			var currentDate = new Date(2020, 0, 30, 19, 5, 30);
			currentDate.setMilliseconds(0);

			// var currentDate = new Date();
			// currentDate.setSeconds(currentDate.getSeconds() - 1);
			// currentDate.setMilliseconds(0);

			reader.readMeasurementBlock(currentDate,function (buffer) {
				var top10 = [];
				var stns = [];
				var offset = offsets[category];

				// Initialize with first 10 measurements
				var index = 0;
				while(top10.length !== 10) {
					var stn = buffer.readUIntBE(index, 4);

					if(!availableStns.includes(stn)) {
						index += reader.getMeasurementSize();
						continue;
					}

					top10.push(buffer.readFloatBE(index+offset));
					stns.push(stn);

					index += reader.getMeasurementSize();
				}

				top10.sort();

				while( !((index+offset+4) > buffer.length) ) {
					var stn = buffer.readUIntBE(index, 4);
					var value = buffer.readFloatBE(index+offset);

					if(!availableStns.includes(stn) || value < top10[0]) {
						index += reader.getMeasurementSize();
						continue;
					}

					for (var i = 1; i < 10; i++) {
						if(value < top10[i]) {
							break;
						}
					}

					top10.splice(i, 0, value);
					top10.shift();
					stns.splice(i, 0, stn);
					stns.shift();

					index += reader.getMeasurementSize();
				}

				var json = '{';
				for (var i = top10.length-1; i >= 0; i--) {
					json += "\""+stns[i]+"\":"+ (Math.round(top10[i] * 100 / 100));

					if(i > 0) {
						json += ",";
					}
				}
				json += '}';

				callback(json);
			});
		});
	});
};

// Main function DEBUG
if (require.main === module) {
	if(process.argv.length < 3) {
		process.stdout.write('usage: node top10.js category');
		process.exit(1);
	}

	module.exports.read(process.argv[2],function (data) {
		process.stdout.write(data);
		process.exit(0);
	});
}