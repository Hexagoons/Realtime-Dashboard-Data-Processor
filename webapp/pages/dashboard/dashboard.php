<!doctype html>
<html lang="nl">
    <head>
        <?php require_component('metadata'); ?>
        
        <title>Dashboard</title>
    </head>
    <body>
        <?php require_component('header', ['container' => false]); ?>
        
        <div class="map-container">
            <div id="map" style="width: 100%; height: 50vh"></div>
        </div>
        
        <div class="container">
            <div class="row">
                <h3>Stations <?= (isset($category)) ? '<small style="font-size: 1.15rem">Top 10 in ' . $category . '</small>' : null ?></h3>
            </div>
    
            <div class="row">
                <?php require_component('dashboard.sidenav'); ?>
        
                <?php if(!isset($category)): ?>
                <?php require_component('dashboard.searchbar', ['route' => '/dashboard']); ?>
                <?php endif; ?>
        
                <div class="col l10">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <?= isset($top10) ? '<th>'.ucfirst($category).'</th>' : null ?>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Country</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($stations); $i++): ?>
                                <tr class='clickable-row'
                                    data-href='/dashboard/stations?id=<?= $stations[ $i ][ 'stn' ] ?>'>
                                    <?= isset($top10) ? '<td>'.$top10[$stations[ $i ][ 'stn' ]].'</td>' : null ?>
                                    <td><?= $stations[ $i ][ 'stn' ] ?></td>
                                    <td><?= $stations[ $i ][ 'name' ] ?></td>
                                    <td><?= $stations[ $i ][ 'country' ] ?></td>
                                    <td><?= $stations[ $i ][ 'latitude' ] ?></td>
                                    <td><?= $stations[ $i ][ 'longitude' ] ?></td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
            
                    <br>
            
                    <?= paginate_links($pageCount, $_GET[ 'page' ] ?? 1); ?>
                </div>
            </div>
        </div>
        
        <?php require_component('footer', ['container' => false]); ?>
        
        <script>
			var map = L.map('map', {
				preferCanvas: true,
				minZoom: 5
			});

			L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(map);

			map.setView([59.75, 24.383], 5);

			var myRenderer = L.canvas({padding: 0.5});
            
            <?php for ($i = 0; $i < count($mapStations); $i++): ?>
			L.circleMarker([<?= $mapStations[ $i ][ 'latitude' ] ?>, <?= $mapStations[ $i ][ 'longitude' ] ?>], {
				renderer: myRenderer
			}).addTo(map)
				.bindPopup("<a href='/dashboard/stations?id=<?= $mapStations[ $i ][ 'stn' ] ?>'><?= ucfirst(strtolower($mapStations[ $i ][ 'name' ])) ?></a> ");
            <?php endfor; ?>
        
        </script>
    </body>
</html>