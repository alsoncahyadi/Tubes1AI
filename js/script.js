$(document).ready(function(){
	//kalo class data diklik...
    $(".data").click(function(event){
    	var matkulId = $(event.target).attr('class').replace('data ','');
    	var initialColorName = "rgba(0, 0, 0, 0)";
    	var changeColorName = "rgb(0, 255, 0)";
    	//Semua class matkulId diubah warnanya jadi ijo
		$("." + matkulId).each(function() {
			if ($(this).css("background-color") != changeColorName) {
				$('input[name=changeMatkul]').val(matkulId);
				$(this).css({"background-color": changeColorName, "border" : "1px solid black", "font-weight" : "bolder"});
			} else { //Kalo udah ijo, diputihin
				$('input[name=changeMatkul]').val("");
				$(this).css({"background-color": initialColorName, "border" : "0px", "font-weight" : "normal"});
			}
		});
    });
});

$(document).ready(function(){
	//kalo class tableharijam diklik...
    $(".tableharijam").click(function(event){
    	var initialColorName = "rgba(0, 0, 0, 0)";
    	var changeColorName = "rgb(255, 255, 0)";
		var hariJam = event.target.id;
		//cell dengan id hariJam dikuningin
		if ($(event.target).attr("class").indexOf("data") == -1) {
			if ($(this).css("background-color") != changeColorName) {
				$('input[name=pindahKe]').val(hariJam);
				$(this).css({"background-color": changeColorName});
			} else {//kalo udah kuning, diputihin
				$('input[name=pindahKe]').val("");
				$(this).css({"background-color": initialColorName});
			}
		} else {
		}
    });
});