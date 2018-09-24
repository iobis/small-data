function degreeToDMS(decimalDegrees){
    var absDecDegrees = Math.abs(decimalDegrees);
    var deg = Math.floor(absDecDegrees);
    var decMinutes = (absDecDegrees - deg)*60;
    var min = Math.floor(decMinutes);
    var decSeconds = (decMinutes-min)*60;
    var sec = (Math.round(decSeconds * 100))/100;
    if(sec==60){min++; sec=0;}
    if (min==60){deg++; min=0; }

    return deg +"º "+min+"' "+sec +"'' ";
}

function convertDMS(lat, lng) {
    var latitude = degreeToDMS(lat);
    var latitudeCardinal = Math.sign(lat) >= 0 ? "N" : "S";

    var longitude = degreeToDMS(lng);
    var longitudeCardinal = Math.sign(lng) >= 0 ? "E" : "W";

    return latitude + " " + latitudeCardinal + "\n" + longitude + " " + longitudeCardinal;
}