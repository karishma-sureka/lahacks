
$( document ).ready(function() {
    console.log("DOC Ready");

    

    $("#info_senti").show();



    $("#img_senti").click(function(){
    	console.log("Sentiment Analysis Clicked.");
    	$("#info_translate").hide();
    	$("#info_news").hide();
    	$("#info_senti").show();
    });

	$("#img_news").click(function(){
    	console.log("News Clicked.");
    	$("#info_translate").hide();
		$("#info_senti").hide();
    	$("#info_news").show();
    });

	$("#img_translate").click(function(){
    	console.log("Translate Clicked.");
    	$("#info_senti").hide();
    	$("#info_news").hide();
    	$("#info_translate").show();
    });


});

