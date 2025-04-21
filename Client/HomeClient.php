<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Chief</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="../cssgen.css" />
</head>

<body>
    <div id="content">
        <?php $page = "Client"; require_once("../www/header.inc.php"); ?>
        <h1>Welcome to the Client Section.</h1>
        <div id="map"></div>
        
        <script>
            const map = L.map('map').setView([46.603354, 1.888334], 6);
            const customIcon = L.icon({
                iconUrl: '../icones/storept.png',
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            function getCoordinates(street, state, callback) {
                let query = encodeURIComponent(`${street} ${state}`);
                let url = `https://nominatim.openstreetmap.org/search?format=json&q=${query}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length > 0) {
                            callback(parseFloat(data[0].lat), parseFloat(data[0].lon));
                        } else {
                            console.warn(`Address not found: ${street}, ${state}`);
                        }
                    })
                    .catch(error => console.error("Geocoding error:", error));
            }

            fetch('https://api.bigdatacloud.net/data/client-ip')
                .then(res => res.json())
                .then(data => {
                    const ip = data.ipString;
                    fetch(`https://api.apibundle.io/ip-lookup?apikey=613e7b4453d541f182c258d6c2676e5c&ip=${ip}`)
                        .then(res => res.json())
                        .then(loc => {
                            const lat = loc.latitude;
                            const lon = loc.longitude;
                            L.marker([lat, lon]).addTo(map).bindPopup("You are here").openPopup();
                            map.setView([lat, lon], 12);
                        })
                        .catch(() => map.setView([46.603354, 1.888334], 6));
                })
                .catch(() => map.setView([46.603354, 1.888334], 6));

            fetch('https://ethan-raulin.alwaysdata.net/api.php?action=stores')
                .then(res => res.json())
                .then(stores => {
                    stores.forEach(store => {
                        getCoordinates(store.street, store.state, (lat, lon) => {
                            L.marker([lat, lon], { icon: customIcon }).addTo(map)
                                .bindPopup(`<b>${store.store_name}</b><br>${store.state}<br>${store.street}`);
                        });
                    });
                })
                .catch(err => console.error('Error loading stores:', err));
        </script>
    </div>
    <?php require_once("../www/footer.inc.php"); ?>
</body>
</html>