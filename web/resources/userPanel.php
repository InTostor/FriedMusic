<script>

  function logout(){
    document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });
    document.location.reload()
}
</script>

<div class="userpanelHolder">
  <div class="userpanelContainer window">
    <div class="title-bar">
      <div class="title-bar-text"><?=$locale->get("UserActionsWindowTitle")?></div>
    </div>
    <div class="window-body">
      <div class="userpanelSection" id="profile">
        <button onclick="logout()" aria-label="Logout"><?=$locale->get("Logout")?></button>


      </div>

      <div class="userpanelSection" id="playlists">
      <?=$locale->get("Playlists")?>
        <div id="userpanelPlaylists" class="userpanelPlaylists sunken-panel">
          <button class="userpanelPlaylist" onclick="playRadio()" style="box-shadow: inset 0 0 0 15px rgb(192, 0, 64);">gachimuchi wave FM</button>
        </div>
      </div>

    </div>
  </div>
</div>

<style>


  .usernamePlaylists{
    display: flex;    
  }
  .userpanelPlaylists{
    width:128px;
    height:256px;
    overflow-x: hidden;
  }
  .userpanelContainer{
    max-width:500px;
  }
  .userpanelPlaylist{
    width:100px;
    height:100px;
    margin:10px;
    padding:15px;
    cursor: pointer;
    aspect-ratio: 1;
    min-width: unset;
  }
</style>
