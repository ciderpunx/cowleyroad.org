<html>
<?php
$dir = "wiki/images"; // change to dir where images live

// This a list of the even/South addresses in the order that they will appear on screen
$even_addresses = range(300,2,2);

// This a list of the odd/North addresses in the order that they will appear on screen
$odd_addresses = range (1,199,2);

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
// TODO: this should actually retrieve the images from "somewhere"
function getAllImageFilenames() {
  global $dir;
  return scandir($dir);
  // return ["78a-2017.jpeg","78a-2017-01.jpg", "78a-2017-04-11.jpg", "78a.png", "23-2016-12.png"];
}

?>
<head>
<title>My cowley rd</title>
<style>
.building-list {height:200px; list-style-type:none; white-space:nowrap; display:inline}
.building-list li {width:20%;display:inline-block}
.building-list img {width:100%;vertical-align:middle}
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

function goRight (el) {
  ml = el.style.marginLeft;
  x="-250";
  if(ml!="") {
    x = parseInt(ml);
  }
  x2 = x-250;
  if (x2 < <?php echo 0 - count($even_addresses) * 250 ?>) {
    x2 = <?php echo 0 - count($even_addresses) * 250 ?>;
  }
  console.log("New X is: " + x2 + " (old was " + x + ")");
  el.style.marginLeft = x2 + "px";
  lazyLoadImages();
}
function goLeft (el) {
  ml = el.style.marginLeft;
  x="0";
  if(ml!="") {
    x = parseInt(ml);
  }
  x2 = x+250;
  if(x2>0) {
    x2=0;
  }
  console.log("New X is: " + x2 + " (old was " + x + ")");
  el.style.marginLeft = x2 + "px";
  lazyLoadImages();
}
</script>

</head>
<body id="main-wrapper">
<h2>North Side (Odd)</h2>
<p style="text-align:center">
<button class="nav" onClick="goLeft(document.getElementById('north-side'));" >&laquo; West</button>
<button class="nav" onClick="goRight(document.getElementById('north-side'));" >East &raquo;</button>
</p>
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
<h2>South Side (Even)</h2>
<p style="text-align:center">
<button class="nav" onClick="goLeft(document.getElementById('south-side'));" >&laquo; East</button>
<button class="nav" onClick="goRight(document.getElementById('south-side'));" >West &raquo;</button>
</p>
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

</body>
