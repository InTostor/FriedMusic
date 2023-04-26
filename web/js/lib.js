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
  let player = document.getElementById('player')
  player.src = trackUrl
  player.onload()
}