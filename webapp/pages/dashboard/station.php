<!doctype html>
<html lang="nl">
    <head>
        <?php require_component('metadata'); ?>
        <title>Dashboard</title>
    </head>
    <body>
        <?php require_component('header', ['container' => false]); ?>
        
        <div class="container-fluid" id="app"
             data-id="<?= $_SESSION[ 'user' ][ 'id' ] ?>"
             data-stn="<?= $station[ 'stn' ] ?>"
             data-token="<?= $_SESSION[ 'user' ][ 'token' ] ?>"
             data-socket-server="<?= APP['socket_server'] ?>"
        >
            <div class="row">
                <h3><?= ucfirst(strtolower($station[ 'name' ])) ?>
                    <span class="new badge" style="float: none; padding: 4px 7px; vertical-align: middle;"><?= ucfirst(strtolower($station[ 'country' ])) ?></span>
                    <?php if(is_role(RESEARCHER) || is_role(ADMIN)): ?>
                        <a href="/dashboard/export?id=<?= $station[ 'stn' ] ?>">
                            <span class="new badge" style="padding: 0 45px">Export <i class="fas fa-file-download" style="margin-left: 6px"></i>
                            </span>
                        </a>
                    <?php endif; ?>
                </h3>
            </div>
            
            <?php $graphs = ['Wind Speed', 'Temperature', 'Snow Falls'] ?>
            <div class="row">
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="col s12 m6 l4">
                        <div class="card chart-card">
                            <div class="card-image waves-effect waves-block waves-light" style="padding-bottom: 5px">
                                <canvas id="chart<?= $i ?>" class="chart"></canvas>
                            </div>
                            <div class="card-tabs">
                                <ul class="tabs tabs-fixed-width">
                                    <li class="tab"><a href="#chart<?= $i ?>_tab1"><?= $graphs[$i-1] ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
            
            <div class="row m-0">
                <h3>Events</h3>
            </div>
    
            <?php $events = ['Tornado', 'Snow', 'Ice'] ?>
            <div class="row">
                <?php for ($i = 4; $i <= 6; $i++): ?>
                    <div class="col s12 m6 l4">
                        <div class="card chart-card">
                            <div class="card-image waves-effect waves-block waves-light" style="padding-top: 0">
                                <canvas id="chart<?= $i ?>" class="chart"></canvas>
                            </div>
                            <div class="card-tabs">
                                <ul class="tabs tab-blue tabs-fixed-width">
                                    <li class="tab"><a href="#chart<?= $i ?>_tab1"><?= $events[$i-4] ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        
        <div class="col l10 w-100">
            <div id="map" style="width: 100%; height: 40vh"></div>
        </div>
        
        <?php require_component('footer', ['container' => false]); ?>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
        <script src="https://www.chartjs.org/samples/latest//utils.js"></script>
        <script src="/js/station-charts.js"></script>
        
        <script>

			var map = L.map('map', {
				preferCanvas: true,
				minZoom: 12
			});

			L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);

			map.setView([<?= $station[ 'latitude' ] ?>, <?= $station[ 'longitude' ] ?>], 12);

			var myRenderer = L.canvas({padding: 0.5});

			L.circleMarker([<?= $station[ 'latitude' ] ?>, <?= $station[ 'longitude' ] ?>], {
				renderer: myRenderer
			}).addTo(map)
				.bindPopup("<a href='/dashboard/stations?id=<?= $station[ 'stn' ] ?>'><?= ucfirst(strtolower($station[ 'name' ])) ?></a> ");
        
        </script>
    
    </body>
</html>