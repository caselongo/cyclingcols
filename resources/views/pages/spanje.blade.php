<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	var markers = [
{number:1, col:"Pico Veleta", lat:"37.055793",lng:"-3.366067"},
{number:2, col:"Port d'Envalira", lat:"42.540248",lng:"1.719492"},
{number:3, col:"Roque de los Muchachos", lat:"28.755131",lng:"-17.885485"},
{number:4, col:"Port de Cabus", lat:"42.549141",lng:"1.422961"},
{number:5, col:"Teide", lat:"28.257642",lng:"-16.618059"},
{number:6, col:"Puerto de Izaña", lat:"28.327087",lng:"-16.489765"},
{number:7, col:"Bola del Mundo", lat:"40.784961",lng:"-3.980061"},
{number:8, col:"Arcalis", lat:"42.621804",lng:"1.478345"},
{number:9, col:"Vallter 2000", lat:"42.425998",lng:"2.264821"},
{number:10, col:"Calar Alto", lat:"37.222059",lng:"-2.546732"},
{number:11, col:"Coll de Pal", lat:"42.302082",lng:"1.920313"},
{number:12, col:"Puerto de la Bonaigua", lat:"42.664087",lng:"0.981889"},
{number:13, col:"Puerto de la Ragua", lat:"37.11433",lng:"-3.031302"},
{number:14, col:"Boi-Taüll", lat:"42.461255",lng:"0.872462"},
{number:15, col:"Puerto de Padilla", lat:"37.263369",lng:"-2.783366"},
{number:16, col:"Alto de la Rabassa", lat:"42.439618",lng:"1.526338"},
{number:17, col:"La Covatilla", lat:"40.356904",lng:"-5.690431"},
{number:18, col:"Torre", lat:"40.322107",lng:"-7.61276"},
{number:19, col:"Alto Campóo (Fuente del Chivo)", lat:"43.045244",lng:"-4.395825"},
{number:20, col:"Valdelinares", lat:"40.380455",lng:"-0.629832"},
{number:21, col:"Pico de las Nieves", lat:"27.960865",lng:"-15.559095"},
{number:22, col:"Alto del Ampriu/Cerler", lat:"42.560608",lng:"0.569091"},
{number:23, col:"Puerto de la Peña Negra", lat:"40.420953",lng:"-5.292665"},
{number:24, col:"Collada de la Gallina", lat:"42.459446",lng:"1.449946"},
{number:25, col:"Rassos de Peguera", lat:"42.141742",lng:"1.76444"},
{number:26, col:"Puerto de los Portillinos", lat:"42.394023",lng:"-6.509957"},
{number:27, col:"Laguna Negra de Neila", lat:"42.052023",lng:"-3.064209"},
{number:28, col:"Javalambre", lat:"40.097813",lng:"-1.014645"},
{number:29, col:"El Travieso", lat:"40.336012",lng:"-5.73213"},
{number:30, col:"Alto Cruz de la Demanda", lat:"42.215743",lng:"-3.101551"},
{number:31, col:"Fonte da Cova", lat:"42.314005",lng:"-6.729562"},
{number:32, col:"Puerto de El Peñón", lat:"42.206523",lng:"-6.552064"},
{number:33, col:"Sierra de la Pandera", lat:"37.633411",lng:"-3.783686"},
{number:34, col:"Puerto de Velefique", lat:"37.235385",lng:"-2.417857"},
{number:35, col:"Pico do Arieiro", lat:"32.735415",lng:"-16.928086"},
{number:36, col:"Puerto de la Morcuera", lat:"40.828513",lng:"-3.832845"},
{number:37, col:"Gamoniteiro", lat:"43.187861",lng:"-5.923511"},
{number:38, col:"Alto de la Sagra", lat:"38.00593",lng:"-2.590928"},
{number:39, col:"Cabeza de Manzaneda", lat:"42.258222",lng:"-7.300121"},
{number:40, col:"Laguna Negra de Urbion", lat:"42.003605",lng:"-2.835264"},
{number:41, col:"Port de Cantó", lat:"42.370552",lng:"1.235221"},
{number:42, col:"Alto de la Farrapona", lat:"43.057036",lng:"-6.089859"},
{number:43, col:"Peña de Francia", lat:"40.513563",lng:"-6.171416"},
{number:44, col:"Puerto de la Cubilla", lat:"42.990358",lng:"-5.905332"},
{number:45, col:"Puerto de Ancares", lat:"42.868416",lng:"-6.819126"},
{number:46, col:"Turó de l'Home", lat:"41.772769",lng:"2.440851"},
{number:47, col:"Puerto de San Glorio", lat:"43.067652",lng:"-4.765295"},
{number:48, col:"La Camperona", lat:"42.853239",lng:"-5.193788"},
{number:49, col:"Puerto de Ventana", lat:"43.058024",lng:"-6.004409"},
{number:50, col:"Sierra de Espuña", lat:"37.862062",lng:"-1.575945"},
{number:51, col:"Port de Larrau", lat:"42.97404",lng:"-0.994226"},
{number:52, col:"Puerto de Serranillos", lat:"40.30748",lng:"-4.94776"},
{number:53, col:"Puerto de Mijares", lat:"40.331497",lng:"-4.813649"},
{number:54, col:"Alto de l'Angliru", lat:"43.244277",lng:"-5.933017"},
{number:55, col:"Orzanzurieta", lat:"43.021976",lng:"-1.276634"},
{number:56, col:"Valdezcaray", lat:"42.255893",lng:"-2.974564"},
{number:57, col:"Serra do Larouco", lat:"41.88032",lng:"-7.720824"},
{number:58, col:"Puerto de San Isidro", lat:"43.065745",lng:"-5.384381"},
{number:59, col:"Alto de Monachil", lat:"37.136575",lng:"-3.473103"},
{number:60, col:"Garajonay", lat:"28.110408",lng:"-17.246947"},
{number:61, col:"Moncalvillo", lat:"42.328632",lng:"-2.613544"},
{number:62, col:"Puerto de Panderruedas", lat:"43.127805",lng:"-4.978928"},
{number:63, col:"Mont Caró", lat:"40.80348",lng:"0.343881"},
{number:64, col:"Puerto de Honduras", lat:"40.221911",lng:"-5.87177"},
{number:65, col:"Alto de Espinho/Marão", lat:"41.248543",lng:"-7.88649"},
{number:66, col:"Puerto de San Lorenzo", lat:"43.141172",lng:"-6.194793"},
{number:67, col:"Alto de Hachueta", lat:"42.952559",lng:"-1.964965"},
{number:68, col:"Portillo de Lunada", lat:"43.17107",lng:"-3.654677"},
{number:69, col:"Alto do Poio", lat:"42.712444",lng:"-7.126212"},
{number:70, col:"Collada Barreda", lat:"43.240549",lng:"-4.720155"},
{number:71, col:"Cabra Montés", lat:"36.935365",lng:"-3.725476"},
{number:72, col:"Alto do Trevim", lat:"40.089761",lng:"-8.179407"},
{number:73, col:"Ermita de Alba", lat:"43.185117",lng:"-5.955098"},
{number:74, col:"Puerto de las Palomas (Ronda)", lat:"36.787514",lng:"-5.376369"},
{number:75, col:"Santuario del Acebo", lat:"43.15858",lng:"-6.499228"},
{number:76, col:"Alto de la Cobertoria", lat:"43.143485",lng:"-5.906042"},
{number:77, col:"Lagos de Covadonga", lat:"43.271675",lng:"-4.982135"},
{number:78, col:"Xorret de Catí", lat:"38.535208",lng:"-0.683964"},
{number:79, col:"Port de Tudons", lat:"38.645138",lng:"-0.308256"},
{number:80, col:"Bocca da Encumeada", lat:"32.7544",lng:"-17.01875"},
{number:81, col:"Puerto de las Peñas Blancas", lat:"36.504447",lng:"-5.188621"},
{number:82, col:"Rocacorba", lat:"42.071978",lng:"2.685986"},
{number:83, col:"Pico del Inglés", lat:"28.533447",lng:"-16.264315"},
{number:84, col:"Puerto del León", lat:"36.812369",lng:"-4.366538"},
{number:85, col:"Nossa Senhora da Graça", lat:"41.416069",lng:"-7.916236"},
{number:86, col:"Puerto de Zafarraya", lat:"36.953092",lng:"-4.123021"},
{number:87, col:"Alto da Fóia", lat:"37.315875",lng:"-8.594913"},
{number:88, col:"Alto de Puig Major", lat:"39.791409",lng:"2.778442"},
{number:89, col:"Coll de Cals Reis", lat:"39.827661",lng:"2.817551"},
{number:90, col:"Puerto de Urkiola", lat:"43.1",lng:"-2.633333"},
{number:91, col:"Montserrat", lat:"41.59414",lng:"1.839781"},
{number:92, col:"Monte Xiabre", lat:"42.632064",lng:"-8.698342"},
{number:93, col:"Alto de Naranco", lat:"43.384567",lng:"-5.863931"},
{number:94, col:"Coll de Rates", lat:"38.722025",lng:"-0.062016"},
{number:95, col:"Monte da Groba", lat:"42.070214",lng:"-8.83035"},
{number:96, col:"Peña Cabarga", lat:"43.378465",lng:"-3.779039"},
{number:97, col:"Monte do Faro", lat:"42.022189",lng:"-8.593673"},
{number:98, col:"Alto de Arrate", lat:"43.205458",lng:"-2.446992"},
{number:99, col:"Jaizkibel", lat:"43.350774",lng:"-1.851441"},
{number:0, col:"Cresta del Gallo", lat:"37.94325",lng:"-1.091166"},
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
					var nr = 100+data.number;
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