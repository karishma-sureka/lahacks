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
        <script type="text/javascript" src="https://www.moxtra.com/api/js/moxtra-latest.js" id="moxtrajs"></script>
        <script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
        
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
                                            <img id="img_news" class="tool_icon" src="../images/bulb.png"></img>
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
                                <div id="senti_stats" class="info_half">
                                </div>
                                <div id="senti_single" class="info_half">
                                </div>
                            </div>
                            <div id="info_news" class="info_box">
                                <div id="news_wiki" class="info_half">
                                    <h1>Some title</h1>
                                    <p>This is a description of this title and it can be really long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and longand long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and longand long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long and long.
                                    </p> 
                                </div>
                                <div id="news_bing" class="info_half">
                                </div>
                            </div>
                            <div id="info_translate" class="info_box">
                                <div id="translate_in" class="info_half">
                                </div>
                                <div id="translate_out" class="info_half">
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
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	    <script src="../js/main.js" id="mainjs"></script>
    </body>   
</html>
