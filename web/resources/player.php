<!-- A modal dialog containing a form -->
<dialog id="favDialog">
  <form>
    <p>
      <label><?=$locale->get("AddTrackToPlaylistModal")?>:
        <select id="playlistSelectModal">
        </select>
      </label>
    </p>
    <div>
      <button value="cancel" formmethod="dialog"><?=$locale->get("Cancel")?></button>
      <button id="confirmBtn" value="default"><?=$locale->get("Confirm")?></button>
    </div>
  </form>
</dialog>
<output></output>


<div class="playerHolder"id="playerHolder">
  <div class="playerContainer col window">
    <audio id="playerSoundMaker" preload="none" ontimeupdate="a.updateSeeker(this.currentTime)" ondurationchange="a.setSeekerRange()" onended="a.trackEnded()"></audio>
    <div class="title-bar">
      <div class="title-bar-text"><?=$locale->get("PlayerWindowTitle")?></div>
    </div>
    <div class="window-body">
      <div class="playerSection col">
        <div class="playerSectionUpper col">

          <p id="playerTrackMeta"class="playerTrackMeta"><?=$locale->get("NoTrackText")?></p>
          <div class="seekerSection row">
            <div class="playerControls row">
              <button id="playerPrevious"class="playerTrackActionButton" onclick="a.prev()" title="Previous track"><img class="playerTrackActionIco" src="/resources/Fast_backward_font_awesome.svg"alt="previous track"></button>
              <button id="playerPlaypause"class="playerTrackActionButton" onclick="a.playPause()" title="Play/Pause"><img class="playerTrackActionIco" src="/resources/Octicons-playback-play.svg" alt="Play track" ></button>
              <button id="playerNext"class="playerTrackActionButton" onclick="a.next()" title="Next track"><img class="playerTrackActionIco" src="/resources/Fast_forward_font_awesome.svg"alt="next track"></button>
            </div>
            <label for="playerSeeker"><?=$locale->get("Seeker")?></label>
            <input id="playerSeeker" oninput="a.seekTo(this.value)" type="range" min="0" max="11" value="5" title="seek to"/>
          </div>

        </div>
        <div class="playerSectionBottom row">
          <p id="playerTimeElapsed"class="playerTimeElapsed">00:00</p>
          <div class="playerVolumeSection col playerBottomCol">
            <input id="playerVolumeRange"type="range" min="0" max="100" value="5" onchange="a.setVolume(this.value)"oninput="a.setVolume(this.value)" style="margin: auto 0 auto 0;" title="Set volume">
            <label id="playerVolumeText"for="playerVolumeRange"><?=$locale->get("Volume")?></label>
          </div>
          <div class="playerActionsSection playerBottomCol">
            <button id="playerActionFavourite" class="playerTrackActionButton" onclick="a.toFavourite()" title="add/remove track from favourites"><img class="playerTrackActionIco" src="/resources/directory_favorites-2.png" alt="add/remove track fom playlist"></button>
            <button id="playerActionToPlaylist" class="playerTrackActionButton"onclick="a.addToPlaylist('')" title="add current track to playlist"><img class="playerTrackActionIco" src="/resources/directory_open_file_mydocs-4.png" alt="add current track to playlist"></button>
            <button id="playerShuffle" class="playerTrackActionButton" onclick="a.shufflePlay(this)" title="shuffle"><img class="playerTrackActionIco" src="/resources/shuffle.svg" alt="shuffle"></button>
            <button id="playerLoopPlaylist" class="playerTrackActionButton" onclick="a.loopList(this)" title="loop playlist"><img class="playerTrackActionIco" src="/resources/repeat.svg" alt="loop playlist"></button>
            <button id="playerLoopTrack" class="playerTrackActionButton" onclick="a.loopTrack(this)" title="loop track"><img class="playerTrackActionIco" src="/resources/repeatOnce.svg" alt="loop track"></button>
          </div>
        </div>
      </div>
      <div class="playlistSection">

        <div class="title-bar playListTitlebar">
          <div class="title-bar-text"><?=$locale->get("CurrentPlaylistSubwindowTitle")?></div>
          <div class="title-bar-controls">
            <button id="playerMinimizePlaylist"aria-label="Minimize currently playing playlist" onclick="a.cyclePlaylistVisibility()"></button>
          </div>
        </div>

        <div id="playerPlaylistHolder"class="sunken-panel">

        </div>
      </div>
    </div>
  </div>
</div>


<style>
  *{
    transition: all ease 0.5s;
  }
.playerContainer{
  min-width:250px;
  max-width:500px;
  padding:2px;
}
.playerSection{
  min-height:100px;
  width:100%;
  overflow: unset;
}

.col{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.row{
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}

.playerBottomCol{
  width:40%;
}

.playerTrackActionButton{
  height: 28px;
  width: 28px;
  min-width: unset;
  padding: 0px;
  margin: 0px;
}
.playerTrackActionIco{
  height: 22px;
  margin: 2px;
}
#playerPlaylistHolder{
  height:30px;
  resize: vertical;
  transition: resize linear 0s;
}
</style>


<script src="/js/playerCombine.js"></script>
<script>
  a = new AudioPlayer()
</script>