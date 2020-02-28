<!doctype html>
<html lang="nl">
    <head>
        <?php require_component('metadata'); ?>
        
        <title>Dashboard</title>
    </head>
    <body>
        <?php require_component('header'); ?>
        
        <div class="row">
            <h3>Stations</h3>
        </div>
        
        <div class="row">
            <?php require_component('dashboard.sidenav'); ?>
    
            <?php require_component('dashboard.searchbar', ['route' => '/dashboard/map']); ?>
            
            <div class="col l10 w-100">
                <div id="map" style="width: 100%; height: 80vh">
            </div>
        </div>
        
        <?php require_component('footer'); ?>
    
        <script>
			var map = L.map('map', {
				preferCanvas: true,
				minZoom: 5
            });

			L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);

			map.setView([55.75, 24.383], 5);

			var myRenderer = L.canvas({ padding: 0.5 });
            
            <?php for ($i = 0; $i < count($stations); $i++): ?>
                L.circleMarker([<?= $stations[ $i ][ 'latitude' ] ?>, <?= $stations[ $i ][ 'longitude' ] ?>], {
                    renderer: myRenderer
                }).addTo(map)
                    .bindPopup("<a href='/dashboard/stations?id=<?= $stations[ $i ][ 'stn' ] ?>'><?= ucfirst(strtolower($stations[ $i ]['name'])) ?></a> ");
            <?php endfor; ?>

        </script>
    </body>
</html>