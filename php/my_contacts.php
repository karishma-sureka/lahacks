<?php
include_once "config.inc.php";
include_once "functions.inc.php";
?>
<html>
    <head>
        <title>My Contacts | Quick Chat</title>
        <meta charset="UTF-8" />
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/styles.css">
        <!-- Include Moxtra JavaScript Library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        
        <script type="text/javascript" src="https://www.moxtra.com/api/js/moxtra-latest.js" id="moxtrajs"></script>
        <script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
        
        <!-- Include D3 JavaScript Library -->
        <script type="text/javascript" src="http://d3js.org/d3.v3.min.js"></script>
        <script src="../js/liquidFillGauge.js" id="liquidjs"></script>
        <script src="../js/Donut3D.js" id="donutjs"></script>

        <script src="../js/main.js" id="mainjs"></script>

        <!-- Initialize Moxtra SDK Object -->
		<?php
		$app_login = "SUCCESS";
		if ($app_login == "SUCCESS") {
			$uid = $_REQUEST["uid"];	
			$access_token = get_access_token($uid);
		}
		?>
        <script type="text/javascript">
            var options = {
                mode: "sandbox", 
                client_id: "<?php echo $CLIENT_ID; ?>", //
                access_token: "<?php echo $access_token; ?>",
                invalid_token: function(event) {
                    //Triggered when the access token is expired or invalid
                    console.log("Access Token expired, please generate a new access token!");
                }
            };
            Moxtra.init(options);
        </script>
        <style type="text/css">
            #chat_container div {margin:0px !important; padding:0px !important;}
        </style>
    </head>
    <body>
        <nav class="navbar navbar-inverse">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Quick Chat</a>
            </div>
          </div>
        </nav>
        <div class="container">
            <div class="row" id="contacts">
                <!-- Container to hold the contact list -->
                <div class="col-md-2 boxdiv" id="contact_list">
                    <?php
                    if ($access_token != ""){
                        show_user_contacts($uid);
                    }
                    ?>
                </div>
                <!-- Container to hold the Chat UI -->
                <div class="col-md-6 boxdiv" id="chat_container">

                </div>
                <!-- Container to hold the tools section -->
                <div class="col-md-4 boxdiv" id="chat_tools">
                        <div id="tools_menu">
                            <table>
                                <tr>
                                    <td>
                                        <div id="sentiment">
                                            <img id="img_senti" class="tool_icon" src="../images/senti.png"></img>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="news">
                                            <img id="img_news" class="tool_icon" src="../images/bulb.png" onClick="updateNews();"></img>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="translate">
                                            <img id="img_translate" class="tool_icon" src="../images/translate.png"></img>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>

                        <div id="tools_info" class="info_box">
                            <div id="info_senti">
                                
                                <div id="senti_single" class="info_half">
                                    <svg id="fillgauge" width="40%" height="200px" onclick="update_gauges();"></svg>
                                    <img src="../images/neutral.png" id="smiley"><img/>
                                    <div id="senti_label"><strong>Sentiment meter</strong></div>
                                </div>
                                <hr/>
                                <div id="senti_stats" class="info_half">
                                </div>
                                
                            </div>
                            <div id="info_news" class="info_box">
                                <div id="news_wiki" class="info_half"> 
                                </div>
                                <hr/>
                                <div id="news_bing" class="info_half">
                                </div>
                            </div>
                            <div id="info_translate" class="info_box">
                                <div id="translate_in" class="info_half">
                                </div>
                                <hr/>
                                <div id="translate_out" class="info_half">
                                    <textarea rows="2" cols="50" id="translate_this" placeholder="Enter message to translate"></textarea>
                                    <p><input value="Translate" type="submit" id="translate-button" onclick="translateOutgoing();"></p>    
                                    <div id='out-translated-msg'></div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>            
        </div>
	    <script type="text/javascript">
        function start_chat (contact_id) {
            var chat_options = {
                unique_id: contact_id,
                iframe: true,
                tagid4iframe: "chat_container",
                iframewidth: "550px",
                iframeheight: "600px",                
                autostart_note: false,
                start_chat: function(event) {
                    console.log("Chat started binder ID: " + event.binder_id);
                },
                publish_feed: function(event) {
                    console.log(event.message + " " + event.binder_id);
                },
                receive_feed: function (event) {
                    console.log(event.message + " " + event.binder_id);
                },
                error: function(event) {
                    console.log("Chat error code: " + event.error_code + " error message: " + event.error_message);
                }
            };            
            Moxtra.chatView(chat_options);
        }

        var sentiment_values = [];

        var config1 = liquidFillGaugeDefaultSettings();
        config1.circleColor = "#34495e";
        config1.textColor = "#34495e";
        config1.waveTextColor = "#ecf0f1";
        config1.circleThickness = 0.1;
        config1.textVertPosition = 0.5;
        config1.waveAnimateTime = 600;
        var gauge= loadLiquidFillGauge("fillgauge", 0, config1);
        
        function NewValue(){
            if(Math.random() > .5){
                return Math.round(Math.random()*100);
            } else {
                return (Math.random()*100).toFixed(1);
            }
        }

        function update_gauges(){
            var new_val = NewValue();
            hsl_val = (new_val/100.0*120) ^ 0;
            $('g circle').css('fill','hsl('+hsl_val+', 80%, 40%)');
            sentiment_values.push(parseFloat(new_val));
            gauge.update(new_val);
            update_smiley(new_val);
            changeData();
            console.log(sentiment_values);
        }

        function update_smiley(val){
            if (val<35){
                $("#smiley").attr('src','../images/sad.png');
            }else if (val<65){
                $("#smiley").attr('src','../images/neutral.png');
            }else{
                $("#smiley").attr('src','../images/happy.png');
            }
        }


        //Donut3D
        var salesData=[
            {label:"Positive", color:"#27ae60"},
            {label:"Neutral", color:"#3498db"},
            {label:"Negative", color:"#e74c3c"}
        ];

        var svg = d3.select("#senti_stats").append("svg").attr("width",700).attr("height",300).attr('id','donut');

        svg.append("g").attr("id","salesDonut");

        Donut3D.draw("salesDonut", randomData(), 150, 150, 100, 80, 30, 0.4);
            
        function changeData(){
            Donut3D.transition("salesDonut", getNewData(), 100, 80, 30, 0.4);
        }

        function randomData(){
            return [
            {label:"Positive", value: 1, color:"#27ae60"},
            {label:"Neutral", value: 1, color:"#3498db"},
            {label:"Negative", value: 1, color:"#e74c3c"}
        ];/*salesData.map(function(d){ 
                return {label:d.label, value:1000*Math.random(), color:d.color};});
*/
        }

        function getNewData(){
            pos = 0;neg=0;neut=0;
            for( i in sentiment_values){
                if (sentiment_values[i]<35){
                    neg+=1;
                }else if (sentiment_values[i]<65){
                    neut+=1;
                }else{
                    pos+=1;
                }
            }
            console.log(pos+" "+neg+" "+neut);
            return [
            {label:"Positive", value: pos, color:"#27ae60"},
            {label:"Neutral", value: neut, color:"#3498db"},
            {label:"Negative", value: neg, color:"#e74c3c"}
            ];

        }

        //Update News from Bing and Wiki
        var wiki_title = "Some title";
        var wiki_body = "Some body some body some body some body some body some body some body some body";
        var wiki_link = "http://www.bing.com";

        var bing_titles = ["The Hateful Eight", "Some other movie", "Another movie","The Hateful Eight", "Some other movie", "Another movie","The Hateful Eight", "Some other movie", "Another movie"];
        var bing_snippets = ["It is a good movie", "This is also a good movie", "This isn't bad either","It is a good movie", "This is also a good movie", "This isn't bad either","It is a good movie", "This is also a good movie", "This isn't bad either"];
        var bing_links = ["http://www.bing.com", "http://www.bing.com", "http://www.bing.com","http://www.bing.com", "http://www.bing.com", "http://www.bing.com","http://www.bing.com", "http://www.bing.com", "http://www.bing.com"];

        function updateNews(){
            var wiki_html = '<h1>'+wiki_title+'</h1><p>'+wiki_body+'</p><a href="'+wiki_link+'">Read more</a>';
            var bing_html = "";
            var i;
            for(i = 0; i<bing_titles.length; i++){
                bing_html = bing_html + '<h3><a href="'+bing_links[i]+'">'+bing_titles[i]+'</a></h1><p>'+bing_snippets[i]+'</p>';
            }
            $("#news_wiki").html(wiki_html);
            $("#news_bing").html(bing_html);
        }

        translateIncoming("Este es un mensaje de muestra para fines de demostración.");
        function translateIncoming(message){
            var translated = "This is a sample message for demonstration purposes.";
            var incoming = "<div id='original-msg'><h2>Original message:</h2><p>"+message+"</p></div><div id='translated-msg'><h2>Translated message:</h2><p>"+translated+"</p></div>";
            $("#translate_in").html(incoming);
        }

        function translateOutgoing(){
            var message = $("#translate_this").val();
            var outgoing = "<p>"+message+"</p>";
            $("#out-translated-msg").html(outgoing);
        }

        </script>
    </body>   
</html>
