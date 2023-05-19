

SAVETOHISTORYTIME = 15

class AudioPlayer {
  loop
  shuffle
  srcType
  src
  savedToHistory
  trackNumber

  
  constructor(loop="none",shuffle=false,srcType=null,src="",trackNumber = null){

    // html elements
    self.EtrackInfo = $("playerTrackMeta")
    self.Eseeker = $("playerSeeker")
    self.Etime = $("playerTimeElapsed")
    self.Evolume = $("playerVolumeRange")
    self.EfavouriteButton = $("playerActionFavourite")
    self.EtoPlaylistButton = $("playerActionToPlaylist")
    self.EminimizePlaylistButton = $("playerMinimizePlaylist")
    self.EplaylistHolder = $("playerPlaylistHolder")
    self.EsoundMaker = $("playerSoundMaker")
    // properties
    self.loop = loop
    self.shuffle = shuffle
    self.musicRoot = "/Music/"
    self.srcType = srcType
    self.src = src
    self.savedToHistory = false
    self.trackNumber = trackNumber
    // listeners & handlers
    navigator.mediaSession.setActionHandler('play', () => this.play());
    navigator.mediaSession.setActionHandler('pause', () => this.pause());
    // navigator.mediaSession.setActionHandler('seekbackward', () => console.log('seekbackward'));
    // navigator.mediaSession.setActionHandler('seekforward', () => console.log('seekforward'));
    // navigator.mediaSession.setActionHandler('seekto', () => console.log('seekto'));
    navigator.mediaSession.setActionHandler('previoustrack', () => this.prev());
    navigator.mediaSession.setActionHandler('nexttrack', () => this.next());

    // set up
    self.EsoundMaker.volume = self.Eseeker.value/100
  }
  serialize(){
    return [self.loop,self.shuffle,self.srcType,self.src,self.trackNumber]
  }

  use(type,src="",trackNumber=0,playlistName=""){
    self.srcType = type
    self.src = src
    self.trackNumber = trackNumber
    self.srcName = playlistName
    if (self.srcType=="radio"){
      self.srcName = "gachiWave.fm"
    }
  }

  getTrack(){
    switch (self.srcType){
      case "track":
        self.EplaylistHolder.innerHTML = ""
        return self.musicRoot + src
      case "radio":
        self.EplaylistHolder.innerHTML = ""
        return self.musicRoot + syncFetch("/api/getNextTrack.php")
      case "playlist":
        this.drawPlaylist(self.src)
        self.currentTrackName = src[self.trackNumber]
        return self.musicRoot + src[self.trackNumber]

    }
  }
  drawPlaylist(playlist){
    let table = document.createElement("table")
    let trackField
    let trackPlay
    let trackNumber = 0
    let e
    playlist.forEach(track => {
      
      e = document.createElement("tr")
      trackField = document.createElement("td")
      trackField.innerHTML=track
      trackPlay = document.createElement("button")
      trackPlay.setAttribute('onclick',`a.setTrackNumber(${trackNumber})`)
      trackPlay.innerHTML = `<img class="playerTrackActionIco" src="/resources/Octicons-playback-play.svg">`
      trackPlay.className = "playerTrackActionButton"
      e.appendChild(trackField)
      e.appendChild(trackPlay)
      table.appendChild(e)
      trackNumber+=1
    });
    self.EplaylistHolder.replaceChildren(table)
    this.updateTrackInfo()
  }

  setTrackNumber(tn){
    self.trackNumber = tn
    this.loadTrackIntoMusician()
    this.play()
  }

  playPause(){
    if (!self.EsoundMaker.paused){
      this.pause()
      
    }else{
      this.play()
      
    }
  }

  play(){
    self.EsoundMaker.play()
    self.playing = self.EsoundMaker.playing
    this.setSeekerRange(self.EsoundMaker.duration)
    this.updatePlayIcon()
  }
  pause(){
    self.EsoundMaker.pause()
    self.paused = self.EsoundMaker.paused
    this.setSeekerRange(self.EsoundMaker.duration)
    this.updatePlayIcon()
  }

  updatePlayIcon(){
    if (self.EsoundMaker.paused){
      $('playerPlaypause').lastChild.src = "/resources/Octicons-playback-play.svg"
    }else{
      $('playerPlaypause').lastChild.src = "/resources/Octicons-playback-pause.svg"
    }
  }

  prev(){
    if (srcType == "playlist" & self.trackNumber>0){
      self.trackNumber -= 1
    }
    if (srcType=="radio"){
    }
    self.savedToHistory = false
    this.loadTrackIntoMusician()
    this.play()
  }
  next(){
    if (srcType == "playlist" & self.trackNumber<src.length-1){
      self.trackNumber += 1
    }

    self.savedToHistory = false
    this.loadTrackIntoMusician()
    this.play()
  }

  setVolume(value){

    self.EsoundMaker.volume = (value/100)**2
    self.Evolume.value = value
    $("playerVolumeText").innerText = "volume: "+value+"%"
    setCookie('volume',`${value}`,1825)
  }

  setSeekerRange(dur){
    self.Eseeker.max = dur
  }

  updateSeeker(time){
    
    self.Eseeker.value = time
    const timeobj = new Date(time*1000)
    let timeMS = timeobj.getUTCMinutes().toString().padStart(2, '0') +":"+timeobj.getUTCSeconds().toString().padStart(2, '0')
    self.Etime.innerText = timeMS
    if (time>=SAVETOHISTORYTIME & !self.savedToHistory){
      fetch("/api/addTrackToHistory.php?track="+self.currentTrackName)
      self.savedToHistory = true
    }
  }
  seekTo(time){
    self.EsoundMaker.currentTime = time
  }

  cyclePlaylistVisibility(){
    if (self.EplaylistHolder.style.height == "200px"){
      // self.EplaylistHolder.style.display = null
      self.EplaylistHolder.style.height = 0
    }else{
      // self.EplaylistHolder.style.display = "none"
      self.EplaylistHolder.style.height = "200px"
    }
  }

  loadTrackIntoMusician(){
    self.currentTrack = this.getTrack()
    self.currentTrackName = self.currentTrack.split('/').pop()
    self.EsoundMaker.src = self.currentTrack
    self.EsoundMaker.load()
    self.trackInFavourite = syncFetch("/api/isInFavourite.php?track="+self.currentTrackName)=="true"
    this.updateFavouriteIcon()
    this.updateTrackInfo()
    this.updatePlayIcon()
    this.setSeekerRange(self.EsoundMaker.duration)
    storeObjectToCookie(this.serialize(),"player")
  }

  updateTrackInfo(){
    self.EtrackInfo.textContent = "playing "+self.currentTrackName + " from: " + self.srcName
    document.title = self.currentTrackName
  }

  updateFavouriteIcon(){
    self.trackInFavourite = syncFetch("/api/isInFavourite.php?track="+self.currentTrackName)=="true"
    if (self.trackInFavourite){
      $("playerActionFavourite").lastChild.src = "/resources/directory_favorites-remove-2.png"
    }else{
      $("playerActionFavourite").lastChild.src = "/resources/directory_favorites-2.png"
    }   
  }

  toFavourite(track = self.currentTrackName){
    this.updateFavouriteIcon()
    if (self.trackInFavourite){
      fetch("/api/addTrackToPlaylist.php?playlist=favourite&remove=true&track="+track)
    }else{
      fetch("/api/addTrackToPlaylist.php?playlist=favourite&track="+track)
    }
    this.updateFavouriteIcon()
  }

  addToPlaylist(track = self.currentTrackName){
    alert("not implemented")
  }

  loopList(){
    let act = "inset 1px 1px #0a0a0a,inset -1px -1px #fff,inset 2px 2px gray,inset -2px -2px #dfdfdf"
    let inact = "inset -1px -1px #0a0a0a,inset 1px 1px #fff,inset -2px -2px grey,inset 2px 2px #dfdfdf"
    switch (self.loop){
      case "none":
        self.loop = "playlist"
        $("playerLoopPlaylist").style.boxShadow = act        
        break
      case "playlist":
        self.loop = "none"
        $("playerLoopPlaylist").style.boxShadow = inact  
        break
      case "track":
        self.loop = "playlist"
        $("playerLoopTrack").style.boxShadow = inact
        $("playerLoopPlaylist").style.boxShadow = act 
        break
    }
  }
  loopTrack(){
    let act = "inset 1px 1px #0a0a0a,inset -1px -1px #fff,inset 2px 2px gray,inset -2px -2px #dfdfdf"
    let inact = "inset -1px -1px #0a0a0a,inset 1px 1px #fff,inset -2px -2px grey,inset 2px 2px #dfdfdf"
    switch (self.loop){
      case "none":
        self.loop = "track"
        $("playerLoopTrack").style.boxShadow = act        
        break
      case "track":
        self.loop = "none"
        $("playerLoopTrack").style.boxShadow = inact  
        break
      case "playlist":
        self.loop = "track"
        $("playerLoopPlaylist").style.boxShadow = inact
        $("playerLoopTrack").style.boxShadow = act 
        break
    }
  }
  shufflePlay(){
    //! Not implemented
  }
  trackEnded(){
    switch(self.loop){
      case "none":
        this.next()
        break
      case "playlist":
        if (self.srcType == "playlist"){
          self.trackNumber+=1
          if (self.trackNumber>self.src.length-1){
            self.trackNumber = 0  
            this.loadTrackIntoMusician()      
          }else{
            this.next()
          }
          this.play()
        }
        break
      case "track":
        this.currentTime = 0
        this.play()
        break
    }
  }


}

function $(id){
  return document.getElementById(id)
}


function syncFetch(url){
  const request = new XMLHttpRequest()
  request.open('GET', url,false)
  request.send()
  if (request.status == 200){
    let txt = request.responseText
    return txt
  }else{
    return false
  }
}