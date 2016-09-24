function test() {

}

function btnclicked() {
	alert('CLICKED');
	$.ajax({
	  type: "POST",
	  url: "/aischedule/StrukturData.php",
	  data: { name: "John" }
	}).done(function( msg ) {
	  alert( "Data Saved: " + msg );
	});
}