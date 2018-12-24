<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	var markers = [
{number:1, col:"Ötztaler Gletscherstrasse", lat:"46.943436",lng:"10.926712"},
{number:2, col:"Kaunertal Gletscherstrasse", lat:"46.864064",lng:"10.713294"},
{number:3, col:"Hochtor", lat:"47.081988",lng:"12.842576"},
{number:4, col:"Timmelsjoch", lat:"46.905275",lng:"11.097429"},
{number:5, col:"Mölltaler Gletscherstrasse", lat:"47.019838",lng:"13.00942"},
{number:6, col:"Grosssee", lat:"47.017629",lng:"12.977551"},
{number:7, col:"Oscheniksee", lat:"46.981365",lng:"13.085268"},
{number:8, col:"Grosser Speikkogel", lat:"46.786603",lng:"14.971312"},
{number:9, col:"Staller Sattel", lat:"46.887768",lng:"12.199961"},
{number:10, col:"Eisenthalhöhe", lat:"46.93565",lng:"13.759147"},
{number:11, col:"Bielerhöhe", lat:"46.917617",lng:"10.09291"},
{number:12, col:"Hochstein", lat:"46.821604",lng:"12.700235"},
{number:13, col:"Tauernmoossee", lat:"47.164567",lng:"12.643669"},
{number:14, col:"Arbiskopf", lat:"47.220894",lng:"11.825115"},
{number:15, col:"Kühtai Sattel", lat:"47.21368",lng:"11.023744"},
{number:16, col:"Vent", lat:"46.856046",lng:"10.89158"},
{number:17, col:"Kitzbüheler Horn", lat:"47.475859",lng:"12.42996"},
{number:18, col:"Thurntaler Rast", lat:"46.777434",lng:"12.40639"},
{number:19, col:"Maltatal", lat:"47.079445",lng:"13.344266"},
{number:20, col:"Lucknerhaus", lat:"47.035838",lng:"12.690677"},
{number:21, col:"Falkertsee Hütte", lat:"46.864358",lng:"13.828819"},
{number:22, col:"Hahntenjoch", lat:"47.287402",lng:"10.655567"},
{number:23, col:"Karneralm", lat:"47.00967",lng:"13.790582"},
{number:24, col:"Goldeck", lat:"46.751741",lng:"13.460863"},
{number:25, col:"Speicher-Zillergründl", lat:"47.123235",lng:"12.061308"},
{number:26, col:"Stoderzinken", lat:"47.458316",lng:"13.814317"},
{number:27, col:"Zettersfeld", lat:"46.864299",lng:"12.788552"},
{number:28, col:"Turracher Höhe", lat:"46.913616",lng:"13.87633"},
{number:29, col:"Arlbergpass", lat:"47.129513",lng:"10.210841"},
{number:30, col:"Sölkpass", lat:"47.270989",lng:"14.079574"},
{number:31, col:"Zemmtal", lat:"47.030413",lng:"11.696184"},
{number:32, col:"Flexenpass", lat:"47.15771",lng:"10.164798"},
{number:33, col:"Hochkar", lat:"47.710397",lng:"14.909771"},
{number:34, col:"Furkajoch", lat:"47.266504",lng:"9.832886"},
{number:35, col:"Gerlitzen Alpenstrasse", lat:"46.696076",lng:"13.924316"},
{number:36, col:"Mutterbergalm", lat:"47.011425",lng:"11.155108"},
{number:37, col:"Schönfeld Pass", lat:"46.98251",lng:"13.776067"},
{number:38, col:"Tauernpass", lat:"47.24798",lng:"13.559139"},
{number:39, col:"Pitztal", lat:"46.957791",lng:"10.875729"},
{number:40, col:"Villacher Alpenstrasse", lat:"46.59322",lng:"13.710775"},
{number:41, col:"Rossbrand", lat:"47.41521",lng:"13.477668"},
{number:42, col:"Tschiernockstrasse", lat:"46.853823",lng:"13.565148"},
{number:43, col:"Silzer Sattel", lat:"47.231401",lng:"10.927656"},
{number:44, col:"Dachsteinstrasse", lat:"47.449567",lng:"13.616818"},
{number:45, col:"Hochtannberg Pass", lat:"47.269737",lng:"10.131062"},
{number:46, col:"Weinebene", lat:"46.840204",lng:"15.015987"},
{number:47, col:"Jamnighütte", lat:"47.002079",lng:"13.110611"},
{number:48, col:"Steinplatte", lat:"47.609838",lng:"12.571877"},
{number:49, col:"Klippitztörl", lat:"46.936667",lng:"14.674123"},
{number:50, col:"Lammersdorfer Hütte", lat:"46.816083",lng:"13.62629"},
{number:51, col:"Katschberg", lat:"47.059306",lng:"13.615487"},
{number:52, col:"Tauplitzalm", lat:"47.594848",lng:"14.011167"},
{number:53, col:"Felbertauerntunnel", lat:"47.144597",lng:"12.530659"},
{number:54, col:"Dolomiten Hütte", lat:"46.7901",lng:"12.784003"},
{number:55, col:"Maltaberg", lat:"46.975759",lng:"13.511601"},
{number:56, col:"Loser Alm", lat:"47.660792",lng:"13.786333"},
{number:57, col:"Loferer Alm", lat:"47.597656",lng:"12.643823"},
{number:58, col:"Kolm Saigurn", lat:"47.068368",lng:"12.983473"},
{number:59, col:"Planneralm", lat:"47.404159",lng:"14.199651"},
{number:60, col:"Pillerhöhe", lat:"47.116548",lng:"10.668104"},
{number:61, col:"Brandnertal", lat:"47.067257",lng:"9.754737"},
{number:62, col:"Steinerhütte", lat:"46.829272",lng:"14.632941"},
{number:63, col:"Eisenkappler Hütte", lat:"46.501937",lng:"14.512847"},
{number:64, col:"Gaberl Sattel", lat:"47.107377",lng:"14.916595"},
{number:66, col:"Nassfeldpass", lat:"46.560921",lng:"13.27675"},
{number:67, col:"Hinterhorn Alm", lat:"47.332287",lng:"11.559729"},
{number:65, col:"Gerlospass", lat:"47.242998",lng:"12.110532"},
{number:68, col:"Halltal", lat:"47.325501",lng:"11.473543"},
{number:69, col:"Arthurhaus", lat:"47.40943",lng:"13.128637"},
{number:70, col:"Ehrwalderalm", lat:"47.38481",lng:"10.96907"},
{number:71, col:"Kanzelhöhe", lat:"46.682252",lng:"13.902639"},
{number:72, col:"Faschinajoch", lat:"47.271862",lng:"9.90713"},
{number:73, col:"Grüne Wand Hütte", lat:"47.070867",lng:"11.920399"},
{number:74, col:"Serfaus", lat:"47.038691",lng:"10.614718"},
{number:75, col:"Brennerpass", lat:"47.006392",lng:"11.506241"},
{number:76, col:"Dientner Sattel", lat:"47.391537",lng:"13.056904"},
{number:77, col:"Pfaffensattel", lat:"47.57004",lng:"15.813826"},
{number:78, col:"Namloser Sattel", lat:"47.370222",lng:"10.691064"},
{number:79, col:"Plöckenpass", lat:"46.603415",lng:"12.944895"},
{number:80, col:"Koglereck", lat:"46.668216",lng:"15.010175"},
{number:81, col:"Paulitschsattel", lat:"46.425257",lng:"14.585295"},
{number:82, col:"Lienbach Sattel (Postalm)", lat:"47.6383",lng:"13.4166"},
{number:83, col:"Luschasattel", lat:"46.507824",lng:"14.709697"},
{number:84, col:"Hohentauern", lat:"47.433661",lng:"14.481433"},
{number:85, col:"Gaisberg", lat:"47.803413",lng:"13.111017"},
{number:86, col:"Schanzsattel", lat:"47.448087",lng:"15.621608"},
{number:87, col:"Hirschbichl", lat:"47.550898",lng:"12.795718"},
{number:88, col:"Diex", lat:"46.746144",lng:"14.617363"},
{number:89, col:"Bödele Losenpass", lat:"47.423126",lng:"9.80924"},
{number:90, col:"Schöcklkreuz", lat:"47.205051",lng:"15.48553"},
{number:91, col:"Millrütte", lat:"47.343098",lng:"9.701823"},
{number:92, col:"Sagalm", lat:"47.268537",lng:"11.671429"},
{number:93, col:"Loiblpass", lat:"46.445131",lng:"14.253679"},
{number:94, col:"Schaidasattel", lat:"46.479009",lng:"14.467271"},
{number:95, col:"Preiner Gscheid", lat:"47.675462",lng:"15.723322"},
{number:96, col:"Hohe Wand", lat:"47.830991",lng:"16.014584"},
{number:97, col:"Magdalensberg", lat:"46.728492",lng:"14.428672"},
{number:98, col:"Ebenwald", lat:"47.981743",lng:"15.693853"},
{number:99, col:"Hocheck", lat:"47.994986",lng:"15.953302"},
{number:0, col:"Jauerling", lat:"48.334628",lng:"15.33901"},

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
					var nr = 400+data.number;
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