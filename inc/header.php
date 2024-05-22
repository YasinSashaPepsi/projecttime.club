<?php

class Header {
/**
 * @param $page Page
 */
function __construct($page) {
    $this->page = $page;
}

function navbar($links) {
    echo '<ul class="navbar-nav mr-auto">';
    $request = $this->page->get_requested_page();
    foreach ($links as $page => $title) {
        $li = "li";
        $class = "nav-item";
        if ($this->page->settings->simple_urls) {
            if ("$request.php" === $page) {
                $class .= " active navbar-active";
            }
        } else if ((substr($_SERVER['SCRIPT_NAME'], -strlen($page))) === $page) {
            $class .= " active navbar-active";
        }
        $li .= " class=\"$class\"";

        if ($this->page->settings->header_show_totals && isset($this->count[$page])) {
            $title .= ' <span class="' . $this->page->settings->badge_classes . '">';
            $title .= $this->count[$page];
            $title .= "</span>";
        }
        $page = $this->page->link($page);
        echo "<$li><a class=\"nav-link\" href=\"$page\">$title</a></li>";
    }
    echo '</ul>';
}

function print_header() {
$page = $this->page;
$settings = $page->settings;
if ($page->settings->header_show_totals) {
    $t = $page->settings->table;
    $t_bans = $t['bans'];
    $t_mutes = $t['mutes'];
    $t_warnings = $t['warnings'];
    $t_kicks = $t['kicks'];
    try {
        $sql = "SELECT
            (SELECT id FROM $t_bans ORDER BY id DESC LIMIT 1),
            (SELECT id FROM $t_mutes ORDER BY id DESC LIMIT 1),
            (SELECT id FROM $t_warnings ORDER BY id DESC LIMIT 1),
            (SELECT id FROM $t_kicks ORDER BY id DESC LIMIT 1)";

        if ($page->settings->verify) {
            $sql .= ",(SELECT id FROM " . $t['config'] . " LIMIT 1)";
        }
        $st = $page->conn->query($sql);

        ($row = $st->fetch(PDO::FETCH_NUM)) or die('Failed to fetch row counts.');
        $st->closeCursor();
        $this->count = array(
            'bans.php'     => $row[0],
            'mutes.php'    => $row[1],
            'warnings.php' => $row[2],
            'kicks.php'    => $row[3],
        );
    } catch (PDOException $ex) {
        Settings::handle_error($page->settings, $ex);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Denis @ Ipolotech.Com">
    <meta name="link" content="https://ipolotech.com">
	<meta name="description" content="">
    <link href="<?php echo $this->page->resource('inc/img/favicon.ico'); ?>" rel="shortcut icon">
    <!-- CSS -->
    <link href="<?php echo $this->page->resource('inc/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo $this->page->resource('inc/css/glyphicons.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo $this->page->resource('inc/css/custom.css'); ?>" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/3.5.95/css/materialdesignicons.min.css" />
        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2/dist/clipboard.min.js"></script>
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://ipolotech.com/files/js/diplt.js"></script>
    <script type="text/javascript">
        function withjQuery(tries, f) {
            if (window.jQuery) f();
            else if (tries > 0) window.setTimeout(function () {
                withjQuery(tries - 1, f);
            }, 100);
        }
    </script>
</head>

<div class="header">
<div id="particles-js"></div>
<div class="cyvers-nav">
    <div class="dd-bg" onclick="closeMobile()"></div>
        <div class="modal" id="popup-modal" tabindex="-1" role="dialog"></div><div class="dd-mobile"><li>   <a href="javascript:void(0)" onclick="openMobile()">
        <i class="fas fa-bars"></i><span> Menu</span></a></li></div><ul id="main-nav" class=""><div class="dd-close"><a href="javascript:void(0)" onclick="closeMobile()"><i class="fas fa-times"></i> close</a></div>
                        
                        
                        
                        
                        
                        <li><a href="/" target="_blank"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="/forums" target="_blank"><i class="fas fa-comments"></i> Forums</a></li>
                        <li class="store"><a href="/store" target="_blank"><i class="fas fa-store-alt"></i> Store</a></li>  
                        <li><a href="/vote" target="_blank"><i class="fas fa-thumbs-up"></i> Vote</a></li>
                        <li><a href="/bans" target="_blank"><i class="fas fa-gavel"></i> Bans</a></li>

                    
                    
                    </ul> </div>
    <div class="logo">
        <a href="/"><img height="300px" width="350px" src="inc/img/logo.png"></a>
    </div>
    
    
    <div class="cyvers-count">
    <div class="container">
        <div class="game-count">
            <div class="mc-icon"><i class="mdi mdi-minecraft"></i></div>
            <span id="mc-online">0</span> <span id="text"></span><br>
            <span id="copy" data-clipboard-text="play.hypixel.net" onclick="serverjoin()"></span>
        </div>
        <div class="discord-count">
            <div class="d-icon"><i class="mdi mdi-discord"></i> </div>
            <span id="discord-online">0</span> <span id="donline"></span><br>
            <a id="invite" href="" target="blank"><span id="dclick"></span></a>
        </div>
    </div>
</div>
</div>
    <div class="navbar navbar-expand-lg <?php echo $settings->navbar_classes; ?>">
        <div class="container">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#litebans-navbar"
                    aria-controls="litebans-navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="litebans-navbar">
                <?php
                $this->navbar(array(
                    "index.php"    => $this->page->t("title.index"),
                    "bans.php"     => $this->page->t("title.bans"),
                    "mutes.php"    => $this->page->t("title.mutes"),
                    "warnings.php" => $this->page->t("title.warnings"),
                    "kicks.php"    => $this->page->t("title.kicks"),
                ));
                ?>

            </div>
        </div>
</div>
<?php
if ($page->lang->version <= 0) {
    ?>
    <div class="col-lg-6 container modal-header alert alert-dismissible alert-light">
        <button type="button" class="close text-dark" data-dismiss="alert">×</button>
        <h5 class="text-dark">Warning: Your language file <a class="alert-link">(lang/<?php echo $settings->lang; ?>.php)</a> is outdated. Some messages will not appear correctly.</h5>
    </div>
    <?php
}
}
}
?>
			
			
			
<script>
        var mc_ip = 'play.hypixel.net'; // minecraft server ip
        var mc_port = '25565'; // minecraft server port
        var mc_copy ='click to copy'; // minecraft copy text
		var game_online = 'players online'; // minecraft players online text
        var widget_id = '744154314859347999'; // discord widget id ( Discord Server > Server Settings > Widget > Server ID )
        var d_online = 'members online'; // discord members online
        var d_click = 'click to join'; // discord invite link text
        var invite_link = 'https://cyvers.com/discord'; // discord invite link
        var particles = true; // true / false ( to enable or disable particles)
        var welcome = 'Welcome on Bans Page';
</script>