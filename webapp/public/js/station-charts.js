function getData() {
	return [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
}

function updateChart(chart, data) {
	chart.data.labels.shift();
	chart.data.labels.push('-');

	var dataset = chart.data.datasets[0];
	dataset.data.shift();
	dataset.data.push(data);
	chart.data.datasets[0] = dataset;

	chart.update();
}

var TIMES = ['-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-'];
var config = {
	type: 'line',
	data: {
		labels: TIMES,
		datasets: [{
			label: 'Default',
			backgroundColor: 'rgba(255, 99, 132, 1)',
			borderColor: 'rgba(255, 99, 132, 1)',
			data: getData(),
			fill: false,
		}]
	},
	options: {
		responsive: true,
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'km/h'
				}
			}]
		},
		legend: {
			onClick: (e) => e.stopPropagation()
		}
	}
};

var ctx1 = document.getElementById('chart1').getContext('2d');
config['data']['datasets'][0]['label'] = 'Wind in km/h';
config['data']['datasets'][0]['data'] = getData();
var chart1 = new Chart(ctx1, config);

var ctx2 = document.getElementById('chart2').getContext('2d');
var config = {
	type: 'line',
	data: {
		labels: TIMES,
		datasets: [{
			label: 'Default',
			backgroundColor: 'rgba(255, 99, 132, 1)',
			borderColor: 'rgba(255, 99, 132, 1)',
			data: getData(),
			fill: false,
		}]
	},
	options: {
		responsive: true,
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'C'
				}
			}]
		},
		legend: {
			onClick: (e) => e.stopPropagation()
		}
	}
};
config['data']['datasets'][0]['label'] = 'Temperature in C';
config['data']['datasets'][0]['data'] = getData();
var chart2 = new Chart(ctx2, config);

var ctx3 = document.getElementById('chart3').getContext('2d');
var config = {
	type: 'line',
	data: {
		labels: TIMES,
		datasets: [{
			label: 'Default',
			backgroundColor: 'rgba(255, 99, 132, 1)',
			borderColor: 'rgba(255, 99, 132, 1)',
			data: getData(),
			fill: false,
		}]
	},
	options: {
		responsive: true,
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Cm'
				}
			}]
		},
		legend: {
			onClick: (e) => e.stopPropagation()
		}
	}
};
config['data']['datasets'][0]['label'] = 'Fallen snow in cm';
config['data']['datasets'][0]['data'] = getData();
var chart3 = new Chart(ctx3, config);

function getEventData() {
	return [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
}

var ctx4 = document.getElementById('chart4').getContext('2d');
var chart4 = new Chart(ctx4, {
	type: 'line',
	data: {
		labels: TIMES,
		datasets: [{
			label: 'Tornado',
			steppedLine: true,
			data: getEventData(),
			borderColor: 'rgb(54, 162, 235)',
			fill: false,
		}]
	},
	options: {
		responsive: true,
		title: {
			display: true
		},
		legend: {
			onClick: (e) => e.stopPropagation()
		},
		scales: {
			yAxes: [{
				ticks: {
					max: 2,
					min: 0,
					stepSize: 1
				}
			}]
		}
	}
});

var ctx5 = document.getElementById('chart5').getContext('2d');
var chart5 = new Chart(ctx5, {
	type: 'line',
	data: {
		labels: TIMES,
		datasets: [{
			label: 'Snow',
			steppedLine: true,
			data: getEventData(),
			borderColor: 'rgb(54, 162, 235)',
			fill: false,
		}]
	},
	options: {
		responsive: true,
		title: {
			display: true
		},
		legend: {
			onClick: (e) => e.stopPropagation()
		},
		scales: {
			yAxes: [{
				ticks: {
					max: 2,
					min: 0,
					stepSize: 1
				}
			}]
		}
	}
});

var ctx6 = document.getElementById('chart6').getContext('2d');
var chart6 = new Chart(ctx6, {
	type: 'line',
	data: {
		labels: TIMES,
		datasets: [{
			label: 'Frozen water (ice)',
			steppedLine: true,
			data: getEventData(),
			borderColor: 'rgb(54, 162, 235)',
			fill: false,
		}]
	},
	options: {
		responsive: true,
		title: {
			display: true
		},
		legend: {
			onClick: (e) => e.stopPropagation()
		},
		scales: {
			yAxes: [{
				ticks: {
					max: 2,
					min: 0,
					stepSize: 1
				}
			}]
		}
	}
});

var socket = io($('#app').attr('data-socket-server'), {
	query: {token: $('#app').attr('data-token'), id: $('#app').attr('data-id')}
});

setInterval(function() {
	socket.emit('data-request', $('#app').attr('data-stn'));
}, 1000, socket);

socket.on('data-update', function(message) {
	updateChart(chart1, message['chart1']);
	updateChart(chart2, message['chart2']);
	updateChart(chart3, message['chart3']);

	updateChart(chart4, message['chart4']);
	updateChart(chart5, message['chart5']);
	updateChart(chart6, message['chart6']);
});
