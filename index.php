<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Game Khelo Games</title>
<style>
a, a:visited { color: darkblue; text-decoration: none; }
img { border: 1px solid darkgrey; }
.headline { background-color:   hsl(224, 76%, 45%); color: honeydew; font-size: 120%; font-weight: bold; margin: 0; text-align: center; }
.list { display: flex; flex-wrap: wrap; justify-content: center; margin: 0; }
.game { background-color: white; border: 1px solid black; margin: 5px; padding: 10px 5px 0 5px; text-align: center; width: 118px;}
.game:hover{ background-color:   hsl(224, 76%, 45%);color:white; }
.platform { background-color: transparent; color: grey; font-size: small; padding: 2px; text-align: right; }
</style>
</head>
<body>
    <?php include('hf/header.html');?>
    <div class="headline">Game Khelo Games</div>
    <section>
<div class="list"><a href="wolf3d/"><div class="game"><img src="_logo/wolf3d.png" width="100" height="100" alt="Wolf3d"><br />Wolf3d<br /><small>classic FPS</small><br /><div class="platform">&#9000; </div></div></a><a href="loderunner_totalrecall/lodeRunner.html"><div class="game"><img src="_logo/loderunner_totalrecall.png" width="100" height="100" alt="Lode Runner"><br />Lode Runner<br /><small>run, dig, old style</small><br /><div class="platform">&#9000; </div></div></a><a href="klotski/klotski.puzzle.html"><div class="game"><img src="_logo/klotski.png" width="100" height="100" alt="Klotski"><br />Klotski<br /><small>free the block</small><br /><div class="platform">&#9000; &#128241;</div></div></a><a href="taptaptap/play/"><div class="game"><img src="_logo/taptaptap.png" width="100" height="100" alt="Tap Tap Tap"><br />Tap Tap Tap<br /><small>tap the blue</small><br /><div class="platform">&#9000; &#128241;</div></div></a><a href="phaser-cat/"><div class="game"><img src="_logo/phaser-cat.png" width="100" height="100" alt="Phaser Cat"><br />Phaser Cat<br /><small>fighting feline</small><br /><div class="platform">&#9000; </div></div></a><a href="8queens/"><div class="game"><img src="_logo/game.png" width="100" height="100" alt="8 Queens"><br />8 Queens<br /><small>chess puzzle</small><br /><div class="platform">&#9000; </div></div></a><a href="pacman/"><div class="game"><img src="_logo/game.png" width="100" height="100" alt="pacman"><br />pacman<br /><small>eat the dots</small><br /><div class="platform">&#9000; </div></div></a><a href="polybranch/polybranchweb/"><div class="game"><img src="_logo/polybranch.png" width="100" height="100" alt="PolyBranch"><br />PolyBranch<br /><small>fly thru trees</small><br /><div class="platform">&#9000; </div></div></a></div><br></section>
<?php include('hf/footer.html');?>
</body>
</html>
