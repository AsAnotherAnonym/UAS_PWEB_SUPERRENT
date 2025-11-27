/**
 * SuperRent - Leaflet Map Helper Functions
 */

const LeafletHelper = {
    /**
     * Initialize a draggable map marker
     * @param {string} mapId - Map container ID
     * @param {string} latInputId - Latitude input field ID
     * @param {string} lngInputId - Longitude input field ID
     * @param {number} defaultLat - Default latitude
     * @param {number} defaultLng - Default longitude
     * @param {number} zoom - Map zoom level
     */
    initDraggableMarker: function(mapId, latInputId, lngInputId, defaultLat = -8.6705, defaultLng = 115.2126, zoom = 13) {
        // Initialize map
        const map = L.map(mapId).setView([defaultLat, defaultLng], zoom);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Create draggable marker
        const marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);
        
        // Add custom icon (optional)
        const customIcon = L.icon({
            iconUrl: '/assets/leaflet/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: '/assets/leaflet/images/marker-shadow.png',
            shadowSize: [41, 41]
        });
        
        marker.setIcon(customIcon);
        
        // Update inputs when marker is dragged
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            document.getElementById(latInputId).value = position.lat.toFixed(8);
            document.getElementById(lngInputId).value = position.lng.toFixed(8);
        });
        
        // Update marker when clicking on map
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById(latInputId).value = e.latlng.lat.toFixed(8);
            document.getElementById(lngInputId).value = e.latlng.lng.toFixed(8);
        });
        
        return { map, marker };
    },

    /**
     * Initialize a simple view-only map
     * @param {string} mapId - Map container ID
     * @param {number} lat - Latitude
     * @param {number} lng - Longitude
     * @param {number} zoom - Map zoom level
     * @param {string} popupText - Popup text (optional)
     */
    initSimpleMap: function(mapId, lat, lng, zoom = 15, popupText = '') {
        const map = L.map(mapId).setView([lat, lng], zoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        const marker = L.marker([lat, lng]).addTo(map);
        
        if (popupText) {
            marker.bindPopup(popupText).openPopup();
        }
        
        return { map, marker };
    },

    /**
     * Initialize a map with multiple markers
     * @param {string} mapId - Map container ID
     * @param {Array} locations - Array of {lat, lng, title, description}
     * @param {number} zoom - Map zoom level
     */
    initMultipleMarkers: function(mapId, locations, zoom = 13) {
        if (locations.length === 0) {
            console.warn('No locations provided for map');
            return null;
        }
        
        // Calculate center point
        const latSum = locations.reduce((sum, loc) => sum + parseFloat(loc.lat), 0);
        const lngSum = locations.reduce((sum, loc) => sum + parseFloat(loc.lng), 0);
        const centerLat = latSum / locations.length;
        const centerLng = lngSum / locations.length;
        
        const map = L.map(mapId).setView([centerLat, centerLng], zoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        const markers = [];
        
        locations.forEach(function(location) {
            const marker = L.marker([location.lat, location.lng]).addTo(map);
            
            let popupContent = `<strong>${location.title}</strong>`;
            if (location.description) {
                popupContent += `<br>${location.description}`;
            }
            if (location.url) {
                popupContent += `<br><a href="${location.url}" class="btn btn-sm btn-primary mt-2">View Details</a>`;
            }
            
            marker.bindPopup(popupContent);
            markers.push(marker);
        });
        
        // Fit bounds to show all markers
        if (locations.length > 1) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
        
        return { map, markers };
    },

    /**
     * Search location by address (requires geocoding service)
     * @param {string} address - Address to search
     * @param {function} callback - Callback function with (lat, lng)
     */
    searchAddress: function(address, callback) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    callback(lat, lng);
                } else {
                    alert('Location not found. Please try a different address.');
                }
            })
            .catch(error => {
                console.error('Geocoding error:', error);
                alert('Error searching for location.');
            });
    },

    /**
     * Get user's current location
     * @param {function} callback - Callback function with (lat, lng)
     */
    getCurrentLocation: function(callback) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    callback(lat, lng);
                },
                function(error) {
                    console.error('Geolocation error:', error);
                    alert('Unable to get your location. Please enable location services.');
                }
            );
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    },

    /**
     * Calculate distance between two points (in kilometers)
     * @param {number} lat1 - Latitude of point 1
     * @param {number} lng1 - Longitude of point 1
     * @param {number} lat2 - Latitude of point 2
     * @param {number} lng2 - Longitude of point 2
     */
    calculateDistance: function(lat1, lng1, lat2, lng2) {
        const R = 6371; // Earth's radius in km
        const dLat = this.toRad(lat2 - lat1);
        const dLng = this.toRad(lng2 - lng1);
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(this.toRad(lat1)) * Math.cos(this.toRad(lat2)) *
                  Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c;
        return distance.toFixed(2);
    },

    toRad: function(value) {
        return value * Math.PI / 180;
    }
};

// Export for use in other scripts
window.LeafletHelper = LeafletHelper;