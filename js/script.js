$(document).ready(function(){
    $(".data").click(function(event){
    	var matkulName = $(event.target).attr('class').replace('data ','');
    	//alert(matkulName + " - " + event.target.nodeName);
    	var initialColorName = "rgba(0, 0, 0, 0)";
    	var changeColorName = "rgb(0, 255, 0)";
		//alert($(this).css("background-color"));
		$("." + matkulName).each(function() {
			$('input[name=changeMatkul]').val(matkulName);
			if ($(this).css("background-color") != changeColorName) {
				$(this).css({"background-color": changeColorName, "border" : "1px solid black", "font-weight" : "bolder"});
			} else {
				$(this).css({"background-color": initialColorName, "border" : "0px", "font-weight" : "normal"});
			}
		});
    });
});

//if( $("#test").css('display') == 'block') {