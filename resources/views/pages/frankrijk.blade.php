<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	var markers = [
	{number:1, col:"Col de la Bonette", lat:"44.326897",lng:"6.807412"},
	{number:2, col:"Col de l'Iseran", lat:"45.417328",lng:"7.031192"},
	{number:3, col:"Col Agnel", lat:"44.68491",lng:"6.978182"},
	{number:4, col:"Col du Galibier", lat:"45.064094",lng:"6.407855"},
	{number:5, col:"Col de Granon", lat:"44.9631",lng:"6.610716"},
	{number:6, col:"Col d'Izoard", lat:"44.820988",lng:"6.735274"},
	{number:7, col:"Col de la Lombarde", lat:"44.202618",lng:"7.150463"},
	{number:8, col:"Col de la Cayolle", lat:"44.256933",lng:"6.745074"},
	{number:9, col:"Val Thorens", lat:"45.298149",lng:"6.583741"},
	{number:10, col:"Col d'Allos", lat:"44.297465",lng:"6.593792"},
	{number:11, col:"Col de Tentes", lat:"42.713444",lng:"-0.051025"},
	{number:12, col:"Cime de Coma Morera", lat:"42.355785",lng:"2.024805"},
	{number:13, col:"Lac d'Aumar", lat:"42.840621",lng:"0.143701"},
	{number:14, col:"Col du Petit-Saint-Bernard", lat:"45.683049",lng:"6.885965"},
	{number:15, col:"Col du Tourmalet", lat:"42.908564",lng:"0.145695"},
	{number:16, col:"Col de Vars", lat:"44.536128",lng:"6.703693"},
	{number:17, col:"la Plagne", lat:"45.507259",lng:"6.676466"},
	{number:18, col:"Col des Champs", lat:"44.174449",lng:"6.698023"},
	{number:19, col:"Cirque de Troumouse", lat:"42.72804",lng:"0.095654"},
	{number:20, col:"Col de la Croix de Fer", lat:"45.227513",lng:"6.203235"},
	{number:21, col:"Circuit de l'Authion", lat:"43.998248",lng:"7.427702"},
	{number:22, col:"Port de Pailhères", lat:"42.727793",lng:"1.946949"},
	{number:23, col:"Col de la Madeleine", lat:"45.435649",lng:"6.375526"},
	{number:24, col:"l'Alpe d'Huez", lat:"45.107969",lng:"6.076678"},
	{number:25, col:"Col du Joly", lat:"45.783866",lng:"6.674016"},
	{number:26, col:"Cormet de Roselend", lat:"45.693165",lng:"6.705063"},
	{number:27, col:"Signal de Bisanne", lat:"45.747192",lng:"6.506689"},
	{number:28, col:"Col du Glandon", lat:"45.239717",lng:"6.175395"},
	{number:29, col:"le Mont Ventoux", lat:"44.17351",lng:"5.278816"},
	{number:30, col:"Risoul 1850", lat:"44.623664",lng:"6.631959"},
	{number:31, col:"Avoriaz", lat:"46.19503",lng:"6.768316"},
	{number:32, col:"Superbagnères", lat:"42.768254",lng:"0.577361"},
	{number:33, col:"Col du Pourtalet", lat:"42.805776",lng:"-0.418728"},
	{number:34, col:"Plateau de Beille", lat:"42.725597",lng:"1.689884"},
	{number:35, col:"Col de Mantet", lat:"42.47784",lng:"2.306096"},
	{number:36, col:"Col de la Pierre-Saint-Martin", lat:"42.957459",lng:"-0.801537"},
	{number:37, col:"Port de Balès", lat:"42.874205",lng:"0.500656"},
	{number:38, col:"Montagne de Lure", lat:"44.118957",lng:"5.802019"},
	{number:39, col:"le Mont Colombis", lat:"44.496229",lng:"6.221324"},
	{number:40, col:"Chamrousse", lat:"45.114074",lng:"5.874438"},
	{number:41, col:"Luz-Ardiden", lat:"42.885147",lng:"-0.061504"},
	{number:42, col:"Col d'Aubisque", lat:"42.976489",lng:"-0.339896"},
	{number:43, col:"La Toussuire", lat:"45.256125",lng:"6.256747"},
	{number:44, col:"Col de Gleize", lat:"44.623211",lng:"6.04424"},
	{number:45, col:"Col de Joux Plane", lat:"46.129662",lng:"6.712775"},
	{number:46, col:"Pla d'Adet", lat:"42.813123",lng:"0.294681"},
	{number:47, col:"Col de la Couillole", lat:"44.101243",lng:"7.0225"},
	{number:48, col:"Crêt de Châtillon", lat:"45.797362",lng:"6.107019"},
	{number:49, col:"Collet d'Allevard", lat:"45.39576",lng:"6.127399"},
	{number:50, col:"Col de la Colombière", lat:"45.992724",lng:"6.476828"},
	{number:51, col:"Hautacam", lat:"42.971481",lng:"-0.003093"},
	{number:52, col:"Pas de Peyrol/Puy Mary", lat:"45.114034",lng:"2.671723"},
	{number:53, col:"Col de l'Arpettaz", lat:"45.795495",lng:"6.431815"},
	{number:54, col:"Col d'Azet", lat:"42.791851",lng:"0.380854"},
	{number:55, col:"Port de Larrau", lat:"42.97404",lng:"-0.994226"},
	{number:56, col:"Col d'Agnès", lat:"42.794487",lng:"1.374739"},
	{number:57, col:"Col de Peyresourde", lat:"42.802336",lng:"0.463258"},
	{number:58, col:"le Mont Aigoual", lat:"44.121404",lng:"3.57714"},
	{number:59, col:"Hourquette d'Ancizan", lat:"42.900043",lng:"0.305702"},
	{number:60, col:"Col de Finiels", lat:"44.431543",lng:"3.764173"},
	{number:61, col:"Col de Chaussy", lat:"45.343698",lng:"6.356452"},
	{number:62, col:"Col de la Croix de Boutières", lat:"44.899453",lng:"4.185163"},
	{number:63, col:"Col du Grand Colombier", lat:"45.903428",lng:"5.761915"},
	{number:64, col:"le Mont du Chat", lat:"45.661733",lng:"5.82452"},
	{number:65, col:"Bise", lat:"46.330542",lng:"6.765566"},
	{number:66, col:"Col d'Aspin", lat:"42.942327",lng:"0.327417"},
	{number:67, col:"Col du Pré de la Dame", lat:"44.385863",lng:"3.901771"},
	{number:68, col:"Col du Coq", lat:"45.303229",lng:"5.83527"},
	{number:69, col:"le Mont d'Or", lat:"46.734322",lng:"6.352491"},
	{number:70, col:"le Puy-de-Dôme", lat:"45.771844",lng:"2.965981"},
	{number:71, col:"Col de la Croix Morand", lat:"45.597039",lng:"2.851677"},
	{number:72, col:"Col du Béal", lat:"45.685344",lng:"3.78292"},
	{number:73, col:"Col de Spandelles", lat:"43.0114",lng:"-0.218650"},
	{number:74, col:"Col de Menté", lat:"42.919336",lng:"0.76169"},
	{number:75, col:"Col du Grand Ballon", lat:"47.904865",lng:"7.10317"},
	{number:76, col:"Mont Salève", lat:"46.120177",lng:"6.171611"},
	{number:77, col:"Grand Taureau", lat:"46.922763",lng:"6.418169"},
	{number:78, col:"Col de Rousset", lat:"44.840379",lng:"5.4049"},
	{number:79, col:"Col de Bavella", lat:"41.795888",lng:"9.224739"},
	{number:80, col:"Pic de Nore", lat:"43.423876",lng:"2.463156"},
	{number:81, col:"Col de l'Oeillon", lat:"45.366666",lng:"4.616666"},
	{number:82, col:"Source de Vaumale", lat:"43.762907",lng:"6.261656"},
	{number:83, col:"Col du Platzerwasel", lat:"47.969468",lng:"7.043002"},
	{number:84, col:"Col du Ballon d'Alsace", lat:"47.82033",lng:"6.834777"},
	{number:85, col:"Col du Petit Ballon", lat:"47.985096",lng:"7.121022"},
	{number:86, col:"Col de Burdincurutcheta", lat:"43.06321",lng:"-1.088233"},
	{number:87, col:"le Champ du Feu", lat:"48.394476",lng:"7.26908"},
	{number:88, col:"Côte de la Croix Neuve", lat:"44.504158",lng:"3.506303"},
	{number:89, col:"Col de Marie Blanque", lat:"43.070581",lng:"-0.507702"},
	{number:90, col:"la Planche des Belles Filles", lat:"47.774695",lng:"6.779545"},
	{number:91, col:"Col de la Machine", lat:"44.975105",lng:"5.334891"},
	{number:92, col:"Col de Vence", lat:"43.76057",lng:"7.074773"},
	{number:93, col:"Serra di Pigno", lat:"42.695085",lng:"9.399956"},
	{number:94, col:"Artzamendi", lat:"43.283712",lng:"-1.407024"},
	{number:95, col:"Col de l'Espigoulier", lat:"43.319505",lng:"5.663468"},
	{number:96, col:"Tour de Madeloc", lat:"42.490534",lng:"3.074727"},
	{number:97, col:"Cirque de Navacelles (Blandas)", lat:"43.932756",lng:"3.462315"},
	{number:98, col:"le Mont Bouquet", lat:"44.132832",lng:"4.279738"},
	{number:99, col:"Ménez-Hom", lat:"48.219933",lng:"-4.234254"},
	{number:0, col:"Mûr-de-Bretagne", lat:"48.229881",lng:"-2.9991"}
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
                    var nr = ("000" + data.number);
					nr = nr.substring(nr.length-3,1000);
					if (data.number == 0){nr = "100";}
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