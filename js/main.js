// Membuat peta Leaflet pada elemen HTML ber-ID "map" dan menetapkan pusat serta zoom awalnya.
var map = L.map('map').setView([-6.494653960586451, 106.60828188022703], 11);

// Menyimpan warna yang digunakan berulang kali untuk setiap jenis layer.
var layerColors = {
	lbs: '#14532d', // Warna hijau tua untuk layer Lahan Baku Sawah.
	lsd: '#86c75a', // Warna hijau muda untuk layer Lahan Sawah Dilindungi.
	jalan: '#dc2626', // Warna merah untuk layer jalan.
	kecamatan: '#000000' // Warna hitam untuk batas kecamatan.
};

// Membuat layer peta dasar OpenStreetMap menggunakan alamat tile OSM.
var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
	maxZoom: 19, // Membatasi tingkat pembesaran maksimum layer OSM.
	attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' // Menampilkan atribusi sumber peta.
});
osm.addTo(map); // Menambahkan OSM sebagai peta dasar yang tampil pertama kali.

// Membuat layer citra satelit dari tile Google.
var imagery = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
        maxZoom: 20, // Membatasi pembesaran maksimum citra satelit.
        subdomains:['mt0','mt1','mt2','mt3'] // Memilih subdomain tile agar permintaan peta dapat dibagi.
});

// Membuat layer peta topografi dari tile Google.
var terrain = L.tileLayer('https://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}',{
        maxZoom: 20, // Membatasi pembesaran maksimum peta topografi.
        subdomains:['mt0','mt1','mt2','mt3'] // Memilih subdomain tile agar permintaan peta dapat dibagi.
});

// Mendefinisikan fungsi untuk mengambil dan mengubah data GeoJSON dari suatu URL.
function loadGeoJson(url) {
	return fetch(url).then(function (response) { // Mengirim permintaan jaringan ke URL dan memproses responsnya.
		if (!response.ok) { // Memeriksa apakah server mengembalikan status HTTP yang berhasil.
			throw new Error('HTTP ' + response.status + ' saat memuat ' + url); // Menghentikan proses dengan informasi status dan URL jika permintaan gagal.
		}
		return response.json(); // Membaca isi respons sebagai data JSON.
	});
}

// Memuat data jalan dan kecamatan secara bersamaan sebelum membuat layer peta.
Promise.all([
	loadGeoJson(window.webgisDataUrls.jalan), // Meminta URL data jalan dari konfigurasi global aplikasi.
	loadGeoJson(window.webgisDataUrls.kecamatan) // Meminta URL data kecamatan dari konfigurasi global aplikasi.
]).then(function (datasets) { // Menjalankan fungsi ini setelah kedua data berhasil dimuat.
	var jalanData = datasets[0]; // Mengambil hasil pertama, yaitu data jalan.
	var kecamatanData = datasets[1]; // Mengambil hasil kedua, yaitu data kecamatan.
	var kabupaten = L.geoJSON(kabupatendata, { // Membuat layer batas kabupaten dari data GeoJSON yang tersedia di halaman.
		style: { // Menentukan tampilan garis batas kabupaten.
			color: '#000000', // Mengatur warna garis menjadi hitam.
			weight: 2, // Mengatur ketebalan garis batas.
			dashArray: '10 5 0 5', // Mengatur pola garis putus-putus.
			fill: false // Menonaktifkan pewarnaan area di dalam batas.
		}
	}).addTo(map); // Menambahkan batas kabupaten langsung ke peta.
	var LBS = L.geoJSON(lahanbakusawahdata, { // Membuat layer Lahan Baku Sawah dari data GeoJSON yang tersedia di halaman.
		style: { // Menentukan tampilan bidang Lahan Baku Sawah.
			color: layerColors.lbs, // Mengatur warna garis berdasarkan warna LBS.
			weight: 1, // Mengatur ketebalan garis bidang.
			fillColor: layerColors.lbs, // Mengatur warna isi bidang sesuai warna LBS.
			fillOpacity: 0.7 // Mengatur transparansi isi bidang.
		}
	}).addTo(map); // Menambahkan layer LBS langsung ke peta.
	var LSD = L.geoJSON(lahansawahdilindungidata, { // Membuat layer Lahan Sawah Dilindungi dari data GeoJSON yang tersedia di halaman.
		style: { // Menentukan tampilan bidang Lahan Sawah Dilindungi.
			color: layerColors.lsd, // Mengatur warna garis berdasarkan warna LSD.
			weight: 1, // Mengatur ketebalan garis bidang.
			fillColor: layerColors.lsd, // Mengatur warna isi bidang sesuai warna LSD.
			fillOpacity: 0.7 // Mengatur transparansi isi bidang.
		}
	}).addTo(map); // Menambahkan layer LSD langsung ke peta.
	var kecamatan = L.geoJSON(kecamatanData, { // Membuat layer batas kecamatan dari data yang telah dimuat.
		style: { // Menentukan tampilan garis batas kecamatan.
			color: layerColors.kecamatan, // Mengatur warna garis memakai warna kecamatan.
			weight: 1.5, // Mengatur ketebalan garis batas kecamatan.
			fill: false, // Menonaktifkan pewarnaan area di dalam batas.
			dashArray: '9 5 1 9' // Mengatur pola garis batas menjadi putus-putus.
		},
		onEachFeature: function (feature, layer) { // Menjalankan fungsi untuk setiap fitur kecamatan dan layer Leaflet-nya.
			var name = feature.properties && feature.properties.WADMKC; // Mengambil nama kecamatan jika properti fitur tersedia.
			if (name) { // Memastikan nama kecamatan ada sebelum membuat label.
				layer.bindTooltip(name, { // Memasang tooltip berisi nama kecamatan pada layer.
					permanent: true, // Menampilkan label terus-menerus tanpa perlu kursor diarahkan.
					direction: 'center', // Menempatkan label di bagian tengah layer.
					className: 'kecamatan-label' // Memberikan kelas CSS khusus untuk pengaturan gaya label.
				});
			}
		}
	}).addTo(map); // Menambahkan layer kecamatan langsung ke peta.
	var jalan = L.geoJSON(jalanData, { // Membuat layer jalan dari data yang telah dimuat.
		style: { // Menentukan tampilan garis jalan.
			color: layerColors.jalan, // Mengatur warna jalan berdasarkan warna yang telah ditentukan.
			weight: 1, // Mengatur ketebalan garis jalan.
			lineCap: 'round', // Membuat ujung garis jalan membulat.
			opacity: 0.75 // Mengatur tingkat ketebalan visual/transparansi garis jalan.
		}
	}); // Menyimpan layer jalan agar dapat dikendalikan melalui kontrol layer.

	// Menyiapkan daftar peta dasar yang dapat dipilih pengguna.
	var baseMaps = {
		"OSM": osm, // Menautkan pilihan OSM ke layer OpenStreetMap.
		"Satellite": imagery, // Menautkan pilihan Satellite ke layer citra satelit.
		"Topographic": terrain // Menautkan pilihan Topographic ke layer topografi.
	};

	// Menyiapkan daftar layer data yang dapat ditampilkan atau disembunyikan pengguna.
	var overlayMaps = {
		"Kabupaten": kabupaten, // Menautkan pilihan Kabupaten ke layer batas kabupaten.
		"Kecamatan": kecamatan, // Menautkan pilihan Kecamatan ke layer batas kecamatan.
		"Jalan Bogor": jalan, // Menautkan pilihan Jalan Bogor ke layer jalan.
		"Lahan Baku Sawah": LBS, // Menautkan pilihan ini ke layer Lahan Baku Sawah.
		"Lahan Sawah Dilindungi": LSD // Menautkan pilihan ini ke layer Lahan Sawah Dilindungi.
	};

	L.control.layers(baseMaps, overlayMaps).addTo(map); // Menambahkan kontrol untuk mengganti peta dasar dan mengatur layer data.

	var legend = L.control({ position: 'bottomleft' }); // Membuat kontrol legenda dan menempatkannya di kiri bawah peta.
	legend.onAdd = function () { // Menentukan elemen HTML yang dibuat saat legenda ditambahkan ke peta.
		var div = L.DomUtil.create('div', 'map-legend'); // Membuat wadah legenda dengan kelas CSS map-legend.
		div.innerHTML = // Mengisi wadah dengan judul dan daftar simbol legenda.
			'<strong class="map-legend-title">LEGENDA</strong>' + // Menambahkan judul legenda.
			'<div class="map-legend-item"><svg class="map-legend-icon" width="24" height="18" viewBox="0 0 24 18" aria-hidden="true"><path d="M3 9h18" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-dasharray=" 9 5 1 9"/></svg>Batas Kabupaten</div>' + // Menampilkan simbol garis dan teks batas kabupaten.
			'<div class="map-legend-item"><svg class="map-legend-icon" width="24" height="18" viewBox="0 0 24 18" aria-hidden="true"><path d="M3 9h18" fill="none" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="9 5 1 9"/></svg>Batas Kecamatan</div>' + // Menampilkan simbol garis dan teks batas kecamatan.
			'<div class="map-legend-item"><svg class="map-legend-icon" width="24" height="18" viewBox="0 0 24 18" aria-hidden="true"><rect x="6" y="3" width="12" height="12" rx="1" fill="#14532d"/></svg>Lahan Baku Sawah (LBS)</div>' + // Menampilkan kotak hijau tua dan teks Lahan Baku Sawah.
			'<div class="map-legend-item"><svg class="map-legend-icon" width="24" height="18" viewBox="0 0 24 18" aria-hidden="true"><rect x="6" y="3" width="12" height="12" rx="1" fill="#86c75a"/></svg>Lahan Sawah Dilindungi (LSD)</div>' + // Menampilkan kotak hijau muda dan teks Lahan Sawah Dilindungi.
			'<div class="map-legend-item"><svg class="map-legend-icon" width="24" height="18" viewBox="0 0 24 18" aria-hidden="true"><path d="M3 9h18" fill="none" stroke="#dc2626" stroke-width="3" stroke-linecap="round"/></svg>Jalan Bogor</div>'; // Menampilkan simbol garis merah dan teks Jalan Bogor.
		return div; // Mengembalikan elemen legenda agar dapat ditampilkan Leaflet.
	};
	legend.addTo(map); // Menambahkan legenda ke peta.
}).catch(function (error) { // Menangani kegagalan pemuatan salah satu data atau pembuatan proses pemuatan.
	console.error('Gagal memuat data jalan dan kecamatan:', error); // Mencatat rincian kesalahan ke konsol untuk membantu pemeriksaan.
	window.alert('Data Jalan Bogor dan Kecamatan gagal dimuat. Silakan muat ulang halaman atau periksa koneksi server.'); // Memberi tahu pengguna bahwa data peta gagal dimuat.
});