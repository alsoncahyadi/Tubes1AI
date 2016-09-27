$(document).ready(function(){
	//kalo class data diklik...
    $(".data").click(function(event){
    	var matkulId = $(event.target).attr('class').replace('data ','');
    	matkulId = matkulId.replace(' selecteddata','');
    	var initialColorName = "rgba(0, 0, 0, 0)";
    	var changeColorName = "rgb(0, 255, 0)";
    	//Semua class matkulId diubah warnanya jadi ijo
		$("." + matkulId).each(function() {
			if ($(this).attr("class").indexOf("selecteddata") == -1) {
				$('input[name=changeMatkul]').val(matkulId);
				$(this).addClass('selecteddata');
			} else { //Kalo udah ijo, diputihin
				$('input[name=changeMatkul]').val("");
				$(this).removeClass('selecteddata');
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
			if ($(this).attr("class").indexOf("selectedharijam") == -1) {
				$('input[name=pindahKe]').val(hariJam);
				$(this).addClass("selectedharijam");
			} else {//kalo udah kuning, diputihin
				$('input[name=pindahKe]').val("");
				$(this).removeClass("selectedharijam");
			}
		} else {
		}
    });
});