<html>
<?php
$dir = "wiki/images"; // change to dir where images live

// This a list of the even/South addresses in the order that they will appear on screen
$even_addresses = range(300,2,2);

// This a list of the odd/North addresses in the order that they will appear on screen
// $odd_addresses = range (1,199,2);
$odd_addresses = [  "1","3","7","13","17","21","23","25","29","31","33"
                 , "35","37","51","xxnorth_51a","53","xxnorth_53a","55"
                 , "57","59","65","93","95","99","101","103","105","107"
                 , "109","xxnorth_EOCC","119","121","125","127","129"
                 , "131","133","137","141147","151","xxnorth_151a","159"
                 , "169","171","173","175","179","181","183","185","187"
                 , "189","191","193","205","207","209","211","213","215"
                 , "217","221","235","237","249","251","255","263","265"
                 , "267","xxnorth_bartlemas-Chapel"
                 ];

$addrs = getAllImageFilenames();
$addr_hash = getLatestImages($addrs);

function getLatestImages ($filenames) {
  global $dir;
  foreach ($filenames as &$f) {
    $pathparts = pathinfo($f);

    $bits = explode('-',$pathparts['filename']);
    if(count($bits)==1) { # we just got the address with nothing after (check with nor)
      $f=FALSE;
    }
    else if (count($bits)==2){
      $f = $bits[0] . "-" . $bits[1] . "-00-00." . $pathparts['extension'];
    }
    else if (count($bits)==3){
      $f = $bits[0] . "-" . $bits[1] . "-" . $bits[2] . "-00." . $pathparts['extension'];
    }
    else if (count($bits)==4){
      continue;
    }
    else {
      $f=FALSE;
    }
  }

  sort($filenames);

  $hash = array();
  foreach(array_filter($filenames) as &$f) {
    $f=str_replace("-00","",$f);
    $bits=explode("-",$f);
    $address=$bits[0];
    $north_addr=str_replace("north","1",strtolower($address)); // so addresses tagged xxnorth appear on "odd" side of road
    $south_north_addr=str_replace("south","2",$north_addr);    // so addresses tagged xxsouth appear on "even" side of road
    $numaddress=preg_replace("[^\n]","",$south_north_addr); // filter only numeric part of address to work out if it is even or odd
    if($numaddress%2==0) {
      $hash['evens'][$address]="$dir/$f";
    }
    else {
      $hash['odds'][$address]="$dir/$f";
    }
  }

  return($hash);
}
function getAllImageFilenames() {
  global $dir;
  return scandir($dir);
}

?>
<head>
<title>My cowley rd</title>
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" />
<style>
html,body {font-family: Montserrat, sans-serif;text-align:center;margin:0}
.building-list {height:350px; list-style-type:none; white-space:nowrap; display:inline; margin-left:0; }
.building-list li {text-align: center;width:300px;margin-right:10px;display:inline-block;overflow:hidden}
.building-list img {height:350px;object-position:center;object-fit:contain}
.scroll-nav {width:100%; text-align:center; height:1.5em; margin-bottom: 1em}
#north-slider,#south-slider {width:50%}
#logo {width:100%;max-width:600px;margin:auto}
#front-logobar{width:100%;background-color:#000;color:#eee}
#front-logobar h1 {margin:0}
#front-logobar p {padding:0 0 3em 0;margin-top:-1em}
#front-article {width:100%; overflow-x:hidden; overflow-y:hidden}
<!--[if (lte IE 10)|!(IE)]><!-->
#front-article {overflow-x:scroll}
<![endif]-->
</style>
<script>

// Lazy Loading code from: http://developer.telerik.com/featured/lazy-loading-images-on-the-web/
window.addEventListener("DOMContentLoaded", lazyLoadImages);
window.addEventListener("load", lazyLoadImages);
window.addEventListener("resize", lazyLoadImages);
window.addEventListener("scroll", lazyLoadImages);

function lazyLoadImages() {
  var images = document.querySelectorAll("#main-wrapper img[data-src]"),
      item;
  // load images that have entered the viewport
  [].forEach.call(images, function (item) {
    if (isElementInViewport(item)) {
      item.setAttribute("src",item.getAttribute("data-src"));
      item.removeAttribute("data-src")
    }
  })
  // if all the images are loaded, stop calling the handler
  if (images.length == 0) {
    window.removeEventListener("DOMContentLoaded", lazyLoadImages);
    window.removeEventListener("load", lazyLoadImages);
    window.removeEventListener("resize", lazyLoadImages);
    window.removeEventListener("scroll", lazyLoadImages);
  }
}
// Source: http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport/7557433#7557433
function isElementInViewport (el) {
    var rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
}
</script>

</head>
<body id="main-wrapper">

<header id="front-logobar">
  <h1>
    <img id="logo" src="images/cowleyRoadOrgLogo600px.png" alt="cowleyroad.org" />
  </h1>
  <p>The hidden history of Oxford&#8217;s favourite street</p>
</header>

<article id="front-article">
  <p>Select any building to start exploring, or read <a href="/wiki/index.php/?title=About">about the project</a>.<br />&nbsp;</p>
  <h3>North (odd numbers)</h3>
  <nav class="scroll-nav">
    West
    <input id="north-slider"
           type="range"
           value="0"
           min="0"
           max="<?php echo count($odd_addresses) * 310 ?>"
           step="310"
           onChange="document.getElementById('north-side').style.marginLeft = 0 - document.getElementById('north-slider').value;lazyLoadImages();" />
    East
  </nav>
  <ul id="north-side" class="building-list">
  <?php
  foreach($odd_addresses as $a){
    if(isset($addr_hash['odds'][$a])) {
      print "\t<li><a href=\"/wiki/index.php?title=$a\"><img data-src=\"".$addr_hash['odds'][$a]."\" alt=\"".$a." Cowley Road, Oxford\" /></a></li>\n";
    }
    else {
      print "\t<li><a href=\"/wiki/$a/index.php?title=$a\"><img data-src=\"PATH_TO_DEFAULT_IMAGE.JPG\" alt=\"".$a." Cowley Road, Oxford\" /></a></li>\n";
    }
  }
  ?>
  </ul>
  <h3>South (even numbers)</h3>
  <nav class="scroll-nav">
    East
    <input id="south-slider"
           type="range"
           value="0"
           min="0"
           max="<?php echo count($even_addresses) * 310 ?>"
           step="310" 
           onChange="document.getElementById('south-side').style.marginLeft = 0 - document.getElementById('south-slider').value;lazyLoadImages();" />
    West
  </nav>
  <ul id="south-side" class="building-list">
  <?php
  foreach($even_addresses as $a){
    if(isset($addr_hash['evens'][$a])) {
      print "\t<li><a href=\"/wiki/index.php?title=$a\"><img data-src=\"".$addr_hash['evens'][$a]."\" alt=\"".$a." Cowley Road, Oxford\"/></a></li>\n";
    }
    else {
      print "\t<li><a href=\"/wiki/index.php?title=$a\"><img data-src=\"PATH_TO_DEFAULT_IMAGE.JPG\" alt=\"".$a." Cowley Road, Oxford\" /></a></li>\n";
    }
  }
  ?>
  </ul>
</article>
</body>
