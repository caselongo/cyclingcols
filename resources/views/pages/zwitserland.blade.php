<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	var markers = [
{number:1, col:"Umbrailpass", lat:"46.543069",lng:"10.434501"},
{number:2, col:"Nufenenpass", lat:"46.477674",lng:"8.387558"},
{number:3, col:"Col du Grand-Saint-Bernard", lat:"45.870067",lng:"7.174646"},
{number:4, col:"Furkapass", lat:"46.572379",lng:"8.414839"},
{number:5, col:"Flüelapass", lat:"46.750314",lng:"9.947354"},
{number:6, col:"Lac de Moiry", lat:"46.138458",lng:"7.574121"},
{number:7, col:"Berninapass", lat:"46.410934",lng:"10.026257"},
{number:8, col:"Forcola di Livigno", lat:"46.437235",lng:"10.053315"},
{number:9, col:"Albulapass", lat:"46.583392",lng:"9.841268"},
{number:10, col:"Lago di Naret", lat:"46.478322",lng:"8.575853"},
{number:11, col:"Julierpass", lat:"46.471973",lng:"9.728852"},
{number:12, col:"Col du Sanetsch", lat:"46.330435",lng:"7.28608"},
{number:13, col:"Galm", lat:"46.35854",lng:"7.680932"},
{number:14, col:"Sustenpass", lat:"46.730367",lng:"8.449321"},
{number:15, col:"Mattmark See", lat:"46.048139",lng:"7.955066"},
{number:16, col:"Croix de Coeur (Verbier)", lat:"46.121738",lng:"7.232823"},
{number:17, col:"Grimselpass", lat:"46.573329",lng:"8.340574"},
{number:18, col:"Ofenpass", lat:"46.63939",lng:"10.294861"},
{number:19, col:"Barrage de la Grande-Dixence", lat:"46.08476",lng:"7.404897"},
{number:20, col:"Juf", lat:"46.444676",lng:"9.580504"},
{number:21, col:"Splügenpass", lat:"46.504946",lng:"9.330387"},
{number:22, col:"Sankt Gotthardpass", lat:"46.559509",lng:"8.561004"},
{number:23, col:"Thyon 2000", lat:"46.181828",lng:"7.372921"},
{number:24, col:"San Bernardinopass", lat:"46.494689",lng:"9.169099"},
{number:25, col:"Oberalppass", lat:"46.658533",lng:"8.671488"},
{number:26, col:"Arolla", lat:"46.0236",lng:"7.482531"},
{number:27, col:"Simplonpass", lat:"46.251196",lng:"8.036424"},
{number:28, col:"Barrage de Mauvoisin", lat:"46.002482",lng:"7.343064"},
{number:29, col:"Barrage d'Emosson", lat:"46.069174",lng:"6.938305"},
{number:30, col:"Grosse Scheidegg", lat:"46.667914",lng:"8.114875"},
{number:31, col:"Klausenpass", lat:"46.868053",lng:"8.855754"},
{number:32, col:"Chandolin", lat:"46.252323",lng:"7.591971"},
{number:33, col:"Lukmanierpass", lat:"46.564931",lng:"8.801878"},
{number:34, col:"Melchsee-Frutt", lat:"46.773661",lng:"8.263917"},
{number:35, col:"Turtmanntal", lat:"46.204046",lng:"7.700204"},
{number:36, col:"Arosa", lat:"46.774616",lng:"9.660945"},
{number:37, col:"Engstelensee", lat:"46.775805",lng:"8.344841"},
{number:38, col:"Glaspass", lat:"46.676913",lng:"9.345367"},
{number:39, col:"Samnaun", lat:"46.942971",lng:"10.359118"},
{number:40, col:"Alpe di Gesero", lat:"46.1913",lng:"9.124505"},
{number:41, col:"Malojapass", lat:"46.398244",lng:"9.695169"},
{number:42, col:"Planachaux", lat:"46.15063",lng:"6.833009"},
{number:43, col:"Val Ferret", lat:"45.916435",lng:"7.105194"},
{number:44, col:"Lac de Tseuzier", lat:"46.345302",lng:"7.433003"},
{number:45, col:"Col de la Croix", lat:"46.322784",lng:"7.124802"},
{number:46, col:"Fafleralp", lat:"46.433214",lng:"7.855588"},
{number:47, col:"Col du Lein", lat:"46.109943",lng:"7.156576"},
{number:48, col:"Älggi", lat:"46.801226",lng:"8.235575"},
{number:49, col:"Wolfgangpass", lat:"46.833775",lng:"9.854898"},
{number:50, col:"Le Mont-Tendre", lat:"46.599121",lng:"6.317989"},
{number:51, col:"Glaubenbielenpass", lat:"46.818622",lng:"8.09153"},
{number:52, col:"Gurnigel", lat:"46.726041",lng:"7.442043"},
{number:53, col:"Lago Luzzone", lat:"46.564496",lng:"8.962391"},
{number:54, col:"Le Chasseral", lat:"47.132961",lng:"7.059286"},
{number:55, col:"Ovronnaz", lat:"46.204534",lng:"7.166824"},
{number:56, col:"Malbun (LIE)", lat:"47.10262",lng:"9.597651"},
{number:57, col:"Pragelpass", lat:"47.0003",lng:"8.870411"},
{number:58, col:"Lenzerheidepass", lat:"46.754668",lng:"9.559669"},
{number:59, col:"Col du Pillon", lat:"46.356631",lng:"7.214726"},
{number:60, col:"Glaubenbergpass", lat:"46.893575",lng:"8.108902"},
{number:61, col:"Vorder Höhi", lat:"47.167205",lng:"9.19432"},
{number:62, col:"Axalp", lat:"46.718957",lng:"8.037903"},
{number:63, col:"Anzère", lat:"46.29657",lng:"7.400513"},
{number:64, col:"Col de la Forclaz", lat:"46.057616",lng:"7.00144"},
{number:65, col:"La Barillette", lat:"46.429871",lng:"6.125644"},
{number:66, col:"Col de Jaman", lat:"46.45185",lng:"6.977199"},
{number:67, col:"Jaunpass", lat:"46.591139",lng:"7.341354"},
{number:68, col:"Champex", lat:"46.03109",lng:"7.113422"},
{number:69, col:"Crans-Montana", lat:"46.311175",lng:"7.478209"},
{number:70, col:"Hauta Schia", lat:"46.66709",lng:"7.226836"},
{number:71, col:"Derborence", lat:"46.279729",lng:"7.214713"},
{number:72, col:"Ächerlipass", lat:"46.907046",lng:"8.336811"},
{number:73, col:"Col du Marchairuz", lat:"46.553554",lng:"6.250325"},
{number:74, col:"Col des Mosses", lat:"46.395283",lng:"7.102071"},
{number:75, col:"Col des Planches", lat:"46.09677",lng:"7.124694"},
{number:76, col:"La Dent de Vaulion", lat:"46.67982",lng:"6.350662"},
{number:77, col:"Griesalp", lat:"46.547814",lng:"7.761621"},
{number:78, col:"Ibergeregg", lat:"47.017425",lng:"8.733202"},
{number:79, col:"Leukerbad ", lat:"46.376681",lng:"7.625211"},
{number:80, col:"Alpe di Neggia", lat:"46.107999",lng:"8.845138"},
{number:81, col:"Vallon de Van", lat:"46.140507",lng:"6.99282"},
{number:82, col:"Flumserberg", lat:"47.093762",lng:"9.284574"},
{number:83, col:"Malbun", lat:"47.148371",lng:"9.432406"},
{number:84, col:"Pas des Morgins", lat:"46.248469",lng:"6.847982"},
{number:85, col:"Grenchenberg", lat:"47.231887",lng:"7.396717"},
{number:86, col:"Schwägalp", lat:"47.256253",lng:"9.319705"},
{number:87, col:"Torgon", lat:"46.306138",lng:"6.850567"},
{number:88, col:"Col de l'Aiguillon", lat:"46.792001",lng:"6.466706"},
{number:89, col:"Le Vue-des-Alpes", lat:"47.07266",lng:"6.870274"},
{number:90, col:"Schrina-Hochrugg", lat:"47.141324",lng:"9.265405"},
{number:91, col:"Weissenstein", lat:"47.255108",lng:"7.508317"},
{number:92, col:"Le Mont-Soleil", lat:"47.160963",lng:"6.98577"},
{number:93, col:"Sattelegg", lat:"47.127882",lng:"8.847889"},
{number:94, col:"Schallenberg Pass", lat:"46.826089",lng:"7.796254"},
{number:95, col:"Monte Generoso", lat:"45.907603",lng:"9.002812"},
{number:96, col:"Bachtel", lat:"47.294367",lng:"8.885701"},
{number:97, col:"Balmberg", lat:"47.265962",lng:"7.539943"},
{number:98, col:"Scheltenpass", lat:"47.335667",lng:"7.581747"},
{number:99, col:"Etzel", lat:"47.17409",lng:"8.773044"},
{number:0, col:"Sur la Croix", lat:"47.372793",lng:"7.141404"},


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
					var nr = 300+data.number;
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