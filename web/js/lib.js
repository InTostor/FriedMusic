function updateUrl(newurl,reload=false){
  let a = document.createElement('a')
  a.href= document.location.href 
  let host = a.origin
  history.pushState({}, null, host+newurl);
}

function createTrackButtons(row){
  row.createElement()
}

function playTrack(playerId,trackUrl){
  let player = document.getElementById(playerId)
  player.src = trackUrl
  player.load()
  player.play()

}

// php style global variable
function getUrlParams(){
  var parts = window.location.search.substr(1).split("&");
  var $_GET = {};
  for (var i = 0; i < parts.length; i++) {
      var temp = parts[i].split("=");
      $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
  }
  return $_GET
}

function getMusicPath(){
  return "/Music/"
}

function updatePlayerInfo(infoId,track,playlist){
  document.getElementById(infoId).innerHTML = "Playing: "+track+" from: "+playlist
}

function getCookie(c_name) {
  var c_value = " " + document.cookie;
  var c_start = c_value.indexOf(" " + c_name + "=");
  if (c_start == -1) {
      c_value = null;
  }
  else {
      c_start = c_value.indexOf("=", c_start) + 1;
      var c_end = c_value.indexOf(";", c_start);
      if (c_end == -1) {
          c_end = c_value.length;
      }
      c_value = unescape(c_value.substring(c_start,c_end));
  }
  return c_value;
}