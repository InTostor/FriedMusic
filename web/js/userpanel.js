function playRadio(){
  a.use("radio")
  a.loadTrackIntoMusician()
}
function reqPlayList(playlist,key = 0){
  playlist = syncFetch(playlist).toString().split("\n")
  a.use("playlist",playlist,key,"search")
  a.loadTrackIntoMusician()
}


// hashing function
String.prototype.hash = function() {
var hash = 0,
  i, chr;
if (this.length === 0) return hash;
for (i = 0; i < this.length; i++) {
  chr = this.charCodeAt(i);
  hash = ((hash << 5) - hash) + chr;
  hash |= 0; // Convert to 32bit integer
}
return hash;
}


fetch("/api/getMyPlaylists.php").then((response) => response.text())
.then(text => {drawPlaylists(text)})

const playlistsHolder = $("userpanelPlaylists")


function drawPlaylists(playlistsString){
playlists = playlistsString.split("\n")
playlists.forEach(element => {
  e = document.createElement("div")
  e.className = "userpanelPlaylist"
  var nameHash = element.hash()
  let colR = Math.floor(((nameHash & 0xFF0000) >> 16) /64)*64
  let colG = Math.floor(((nameHash & 0x00FF00) >> 8 ) /64)*64
  let colB = Math.floor( (nameHash & 0x0000FF)        /64)*64

  var color = `rgb( ${colR},${colG},${colB} )`
  console.log(color)

  uname = getCookie('who')
  e.setAttribute('onclick',`reqPlayList('/userdata/${uname}/${element}')`)

  e.style.backgroundColor = color
  e.innerText = element
  playlistsHolder.appendChild(e)
});

}