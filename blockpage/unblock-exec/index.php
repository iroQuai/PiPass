<?php
require('../../config.php');

$GLOBALS['unblockTimeSec'] = $conf['unblock_seconds'];
$hostname = gethostname();
$server_ip = $_SERVER['SERVER_ADDR']; 
$pipass_v = $conf['pipass_v'];

if(isset($_GET['url'])) {
  $url = $_GET['url'];
  $GLOBALS['url'] = $_GET['url'];
  $url_provided = true;
} else {
  $url_provided = false;
}
?>

<!doctype html>
<html lang="en">
  <head>
  <script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.js"></script>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

    <title>Webpage Blocked</title>

    <!--- Inline styles -->
    <style>
        .container {
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            position: absolute;
        }

        #alert {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 95%;
        }

        .toast {
          margin-bottom: 0.5%;
        }

        #toastwrapper {
          margin-top: 1%;
          margin-right: 1%;
        }
    </style>
  </head>
  <body>
    <div class="container">
    <?php
      if($_GET['unblock'] != "unblocked") {
        echo <<<EOL
        <div class="alert alert-warning" id="alert" role="alert">
        <h4 class="alert-heading"><i style="margin-right:1%;" class="fas fa-shield-alt"></i>Unblocking Webpage</h4>
        <p>Currently unblocking the requested page temporarily. Please wait, do <strong>not</strong> refresh the page.
        <br />
        <br />
        <strong>Blacklisted URL: </strong>$url
        <hr>
        <form id="intersittialUnblock" method="GET">
        <input type="hidden" name="unblockurl" value="$url">
        <input type="hidden" name="url" value="$url">
        <input type="hidden" name="unblock" value="unblocked">
EOL;
        
        if(isset($_GET['unblock']) || !($url_provided)) {
          echo <<<EOL
            <button type="submit" style="display:none;" class="btn btn-warning btn-lg btn-block">Unblock Temporarily</button>
            </form>
          </div>
EOL;
        } else {
          echo <<<EOL
            <button type="submit" style="display:none;" class="btn btn-warning btn-lg btn-block">Unblock Temporarily</button>
            </form>
          </div>
EOL;
        }
      } else {
        $localTimeFriendly = $conf['time_friendly'];
        echo <<<EOL
        <div class="alert alert-info" id="alert" role="alert">
        <h4 class="alert-heading"><i style="margin-right:1%;" class="fas fa-check"></i>Webpage Unblocked</h4>
        <p>The webpage <strong>$url</strong> has been unblocked successfully. The website will revert to being blocked in approximately $localTimeFriendly, after which time you may wish to trigger the unblock again.</p>
        <br />
        <strong>If you are still unable to visit the webpage,</strong> try flushing your computer's DNS cache or clearing your browser's cache.
EOL;
        if($conf['show_tech_info'] == true) {
          echo <<<EOL
          <br />
          <br />
          <code style="color:gray">TECHNICAL INFO:</code>
          <br />
          <code style="color:gray">Reported by $hostname ($server_ip) at $date. Running PiPass version $pipass_v.</code>
          </div>
EOL;
        } else {
          echo <<<EOL
          </div>
EOL;
        }
      }
    ?>
        </div>
    </div>
    <div aria-live="polite" aria-atomic="true" id="toastwrapper" style="position: relative; min-height: 200px;">
      <div aria-live="polite" aria-atomic="true">
          <div class="toast" id="requesting-toast" style="position: absolute; top: 0; right: 0;" data-delay="20000">
            <div class="toast-header">
              <strong class="mr-auto">PiPass</strong>
            </div>
            <div class="toast-body">
              Requesting temporary unblock from the server. Do not reload the page, this may take a few seconds.
            </div>
          </div>
        </div>
      <div aria-live="polite" aria-atomic="true">
        <div class="toast" id="success-toast" style="position: absolute; top: 65%; right: 0;" data-delay="20000">
            <div class="toast-header">
              <strong class="mr-auto">PiPass</strong>
            </div>
            <div class="toast-body">
              Success! The page has been unblocked and the block will be reinstated in approximately <?php echo $conf['time_friendly'] ?>. You can trigger another unblock at that time if necessary.
            </div>
          </div>
        </div>
      </div>

      <!--- $('#requesting-toast').toast('show') -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>

<?php
    // Unblocking function

    if($_GET['unblock'] == "unblocked") {
      unblock();
      echo <<<EOL
      <script>
      jQuery(function(){
        $('#requesting-toast').toast('show')
        $('#success-toast').toast('show')
      });
      </script>
EOL;
    } else if($_GET['unblock'] == "true") {
      sleep(1);
      echo <<<EOL
      <script>
      jQuery(function(){
        $('#requesting-toast').toast('show')
      });
      setTimeout(function() {document.getElementById('intersittialUnblock').submit();}, 5000);
      </script>
EOL;
    }
    function unblock() {

      // Build command to add to P-H whitelist
      // $addWhitelistComm = "pihole -w " .$GLOBALS['url'];
      $addWhitelistComm = "sudo pihole -w ".$GLOBALS['url']." > /dev/null &";


      // Build command to schedule removal from P-H whitelist. Sleep x = sleep for x number of seconds. 300 = 5 minutes.
      $rmWhitelistComm = "( sleep ".$GLOBALS['unblockTimeSec']."; sudo pihole -w -d ".$GLOBALS['url']." & ) > /dev/null &";

      // Execute P-H whitelist add command
      exec($addWhitelistComm);

      // Execute command that schedules removal of domain from P-H whitelist
      exec($rmWhitelistComm);

      // All done!
    }
?>