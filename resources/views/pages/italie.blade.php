<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	var markers = [
{number:1, col:"Passo dello Stelvio", lat:"46.528692",lng:"10.45303"},
{number:2, col:"Colle dell'Agnello", lat:"44.68491",lng:"6.978182"},
{number:3, col:"Passo di Gavia", lat:"46.343553",lng:"10.487771"},
{number:4, col:"Colle del Nivolet", lat:"45.478911",lng:"7.142315"},
{number:5, col:"Colle della Fauniera", lat:"44.38136",lng:"7.102545"},
{number:6, col:"Passo del Rombo", lat:"46.905275",lng:"11.097429"},
{number:7, col:"Rifugio Auronzo", lat:"46.613454",lng:"12.295497"},
{number:8, col:"Colle di Lombarda", lat:"44.202618",lng:"7.150463"},
{number:9, col:"Colle di Sampeyre", lat:"44.551244",lng:"7.119296"},
{number:10, col:"Plan de Corones", lat:"46.743121",lng:"11.951709"},
{number:11, col:"Passo di Sella", lat:"46.508765",lng:"11.76628"},
{number:12, col:"Passo Pordoi", lat:"46.487537",lng:"11.813646"},
{number:13, col:"Passo di Giau", lat:"46.48415",lng:"12.051892"},
{number:14, col:"Passo di Pennes", lat:"46.818901",lng:"11.443563"},
{number:15, col:"Passo di Valparola", lat:"46.528743",lng:"11.990336"},
{number:16, col:"Colle delle Finestre", lat:"45.072097",lng:"7.053112"},
{number:17, col:"Campo Imperatore", lat:"42.442537",lng:"13.568234"},
{number:18, col:"Passo della Spluga", lat:"46.504946",lng:"9.330387"},
{number:19, col:"Val Martello", lat:"46.489202",lng:"10.686982"},
{number:20, col:"Passo di Fedaia", lat:"46.457214",lng:"11.879545"},
{number:21, col:"Passo di Stalle", lat:"46.887768",lng:"12.199961"},
{number:22, col:"Passo Manghen", lat:"46.173278",lng:"11.441729"},
{number:23, col:"Blockhaus della Maïella", lat:"42.148833",lng:"14.113827"},
{number:24, col:"Sestriere", lat:"44.956812",lng:"6.880357"},
{number:25, col:"Pian del Re", lat:"44.700851",lng:"7.096138"},
{number:26, col:"Passo delle Erbe", lat:"46.675262",lng:"11.814274"},
{number:27, col:"Val Senales", lat:"46.755248",lng:"10.783578"},
{number:28, col:"Passo San Marco", lat:"46.046897",lng:"9.622666"},
{number:29, col:"Breuil-Cervinia", lat:"45.934352",lng:"7.63109"},
{number:30, col:"Passo di Rolle", lat:"46.296711",lng:"11.788671"},
{number:31, col:"Rifugio Gardecchia", lat:"46.45",lng:"11.633333"},
{number:32, col:"Colle San Carlo", lat:"45.742904",lng:"6.989534"},
{number:33, col:"Passo di San Pellegrino", lat:"46.378859",lng:"11.784492"},
{number:34, col:"Etna", lat:"37.699",lng:"14.998226"},
{number:35, col:"Jafferau", lat:"45.08146",lng:"6.731737"},
{number:36, col:"Monte Terminillo", lat:"42.47387",lng:"13.006089"},
{number:37, col:"Passo di Croce Domini", lat:"45.907527",lng:"10.409718"},
{number:38, col:"Monte Botte Donato", lat:"39.281101",lng:"16.460309"},
{number:39, col:"Montalto", lat:"38.160004",lng:"15.916529"},
{number:40, col:"Passo del Mortirolo", lat:"46.24767",lng:"10.298053"},
{number:41, col:"Alpe di Siusi", lat:"46.540366",lng:"11.61822"},
{number:42, col:"Passo del Vivione", lat:"46.036372",lng:"10.199175"},
{number:43, col:"Cascata del Toce", lat:"46.426617",lng:"8.400844"},
{number:44, col:"Alpe di Pampeago", lat:"46.342116",lng:"11.540671"},
{number:45, col:"Rifugio Barbara Lowrie", lat:"44.749958",lng:"7.079771"},
{number:46, col:"Plan di Montecampione", lat:"45.836376",lng:"10.23862"},
{number:47, col:"Monte Zoncolan", lat:"46.501853",lng:"12.927138"},
{number:48, col:"Monte Penegal", lat:"46.437407",lng:"11.215151"},
{number:49, col:"Monte Grappa", lat:"45.870204",lng:"11.791977"},
{number:50, col:"Monte Amiata", lat:"42.890115",lng:"11.626453"},
{number:51, col:"Campo Carlo Magno", lat:"46.244817",lng:"10.842454"},
{number:52, col:"Monte Bondone", lat:"46.032993",lng:"11.047498"},
{number:53, col:"Val Genova", lat:"46.197745",lng:"10.596736"},
{number:54, col:"Chiareggio", lat:"46.316666",lng:"9.783333"},
{number:55, col:"Piano Battáglia", lat:"37.877462",lng:"14.026451"},
{number:56, col:"San Pellegrino in Alpe", lat:"44.200609",lng:"10.488063"},
{number:57, col:"Prato Nevoso", lat:"44.256667",lng:"7.789686"},
{number:58, col:"Colle del Dragone", lat:"39.902625",lng:"16.117115"},
{number:59, col:"Bruncu Spina", lat:"40.023375",lng:"9.30271"},
{number:60, col:"Passo di Cason di Lanza", lat:"46.56611",lng:"13.171087"},
{number:61, col:"Monte Sirino", lat:"40.144961",lng:"15.838037"},
{number:62, col:"Forca di Presta", lat:"42.790258",lng:"13.261478"},
{number:63, col:"Alpe Giumello", lat:"46.045233",lng:"9.360644"},
{number:64, col:"Monte Nerone", lat:"43.557599",lng:"12.518872"},
{number:65, col:"Alpe Cheggio", lat:"46.087609",lng:"8.113096"},
{number:66, col:"Passo di Campogrosso", lat:"45.728548",lng:"11.173795"},
{number:67, col:"Mottarone", lat:"45.880389",lng:"8.451141"},
{number:68, col:"Rifugio Alpo", lat:"45.810405",lng:"10.583432"},
{number:69, col:"Campitello Matese", lat:"41.461169",lng:"14.396074"},
{number:70, col:"Passo dei Due Santi", lat:"44.383273",lng:"9.774208"},
{number:71, col:"Abetone", lat:"44.14539",lng:"10.664792"},
{number:72, col:"Monte Carpegna", lat:"43.798655",lng:"12.32042"},
{number:73, col:"Monte Catria", lat:"43.469864",lng:"12.697571"},
{number:74, col:"Passo di Pietra Spada", lat:"38.506635",lng:"16.354909"},
{number:75, col:"Monte Fumaiolo", lat:"43.789809",lng:"12.072252"},
{number:76, col:"Monte Limbara", lat:"40.852676",lng:"9.175895"},
{number:77, col:"Passo di Valcava", lat:"45.788346",lng:"9.511646"},
{number:78, col:"Mataiur", lat:"46.202943",lng:"13.539062"},
{number:79, col:"Passo della Calla", lat:"43.860067",lng:"11.744329"},
{number:80, col:"Monte Beigua", lat:"44.4332",lng:"8.56466"},
{number:81, col:"Campo Cecina", lat:"44.124642",lng:"10.086738"},
{number:82, col:"Monte Faito", lat:"40.658471",lng:"14.498134"},
{number:83, col:"Piancavallo", lat:"46.107387",lng:"12.519368"},
{number:84, col:"Santuario di Montevergine", lat:"40.936975",lng:"14.712574"},
{number:85, col:"Alpe Colle", lat:"46.024532",lng:"8.626021"},
{number:86, col:"Oropa", lat:"45.625224",lng:"7.982149"},
{number:87, col:"Passo del Penice", lat:"44.796884",lng:"9.327511"},
{number:88, col:"Punta Veleno", lat:"45.670931",lng:"10.78215"},
{number:89, col:"Colla di Langan", lat:"43.968024",lng:"7.731186"},
{number:90, col:"Santuario Dinnammare", lat:"38.158573",lng:"15.464571"},
{number:91, col:"Monte Petrano", lat:"43.517373",lng:"12.618821"},
{number:92, col:"Campo dei Fiori", lat:"45.868107",lng:"8.777345"},
{number:93, col:"Lago Laceno", lat:"40.807443",lng:"15.114784"},
{number:94, col:"Passo di Genna Silana", lat:"40.15864",lng:"9.508023"},
{number:95, col:"Vesuvio", lat:"40.821956",lng:"14.428879"},
{number:96, col:"Passo della Crocetta", lat:"39.321085",lng:"16.112609"},
{number:97, col:"Monte Serra", lat:"43.754891",lng:"10.553019"},
{number:98, col:"Passo del Ghisallo", lat:"45.925361",lng:"9.268041"},
{number:99, col:"Passo di San Boldo", lat:"46.00678",lng:"12.169591"},
{number:0, col:"Poggio di San Remo", lat:"43.828304",lng:"7.81492"},

    ];
	
    window.onload = function () {
        LoadMap();
    }
    function LoadMap() {
        var mapOptions = {
            //center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
            zoom: 8,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
 
        //Create and open InfoWindow.
        var infoWindow = new google.maps.InfoWindow();
		
		var bounds = new google.maps.LatLngBounds();

        for (var i = 0; i < markers.length; i++) {
		
			var data = markers[i];
            var image = new google.maps.MarkerImage('/markers/marker' + data.number + '.png',
						  new google.maps.Size(20, 34),
						  new google.maps.Point(0, 0),
						  new google.maps.Point(10, 34));
						  
            var myLatlng = new google.maps.LatLng(data.lat, data.lng);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                icon: image,
				title: data.title
            });
			
			bounds.extend(myLatlng);
 
            //Attach click event to the marker.
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
					var nr = 200+data.number;
					if (data.number == 0){nr += 100;}
                    infoWindow.setContent("<div style = 'width:200px;min-height:40px'>" + nr + ": " + data.col + "</div>");
                    infoWindow.open(map, marker);
                });
            })(marker, data);
        }
				
		map.fitBounds(bounds);
 
    }
</script>
<div id="dvMap" style="width: 100%; height: 100%">
</div>