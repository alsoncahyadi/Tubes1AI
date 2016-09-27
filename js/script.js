$(document).ready(function(){
    $(".data").click(function(event){
    	var matkulId = $(event.target).attr('class').replace('data ','');
    	//alert(matkulName + " - " + event.target.nodeName);
    	var initialColorName = "rgba(0, 0, 0, 0)";
    	var changeColorName = "rgb(0, 255, 0)";
		//alert($(this).css("background-color"));
		$("." + matkulId).each(function() {
			if ($(this).css("background-color") != changeColorName) {
				$('input[name=changeMatkul]').val(matkulId);
				$(this).css({"background-color": changeColorName, "border" : "1px solid black", "font-weight" : "bolder"});
			} else {
				$('input[name=changeMatkul]').val("");
				$(this).css({"background-color": initialColorName, "border" : "0px", "font-weight" : "normal"});
			}
		});
    });
});

$(document).ready(function(){
    $(".tableharijam").click(function(event){
    	//alert(matkulName + " - " + event.target.nodeName);
    	var initialColorName = "rgba(0, 0, 0, 0)";
    	var changeColorName = "rgb(255, 255, 0)";
		var hariJam = event.target.id;
		//alert("table hari jam " + hariJam);
		if ($(event.target).attr("class").indexOf("data") == -1) {
		//	alert("MASUK");
			if ($(this).css("background-color") != changeColorName) {
				$('input[name=pindahKe]').val(hariJam);
				$(this).css({"background-color": changeColorName});
			} else {
				$('input[name=pindahKe]').val("");
				$(this).css({"background-color": initialColorName});
			}
		} else {
		//	alert("GAMASUK");
		}
    });
});