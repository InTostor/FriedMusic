<div class="searchHolder">
   <div class="searchContainer window">
     <div class="title-bar">
       <div class="title-bar-text"><?=$locale->get("SearchWindowTitle")?></div>
     </div>
     <div class="window-body">
       <div class="searchTools">
         <label for="searchQuery"><?=$locale->get("Search")?></label>
         <input id="searchQuery" type="text"  placeholder="Oxxxymiron - Что такое империя?">
         <label for="searchQueryType"><?=$locale->get("SearchQueryType")?>:</label>
         <select id="searchQueryType"class="searchQueryType" oninput="s.search()">
           <option>Query</option>
           <option>Artist</option>
           <option>Genre</option>
           <option>Album</option>
         </select>
         <button class="searchSubmitButton" title="search tracks"onclick="s.search()"><img alt="search tracks"class="searchSubmitIco"src="/resources/search_directory-5.png"></button></button>
       </div>
       <div class="sunken-panel" id="searchTableHolder">
       </div>
     </div>
   </div>
</div>

<style>
.searchTools{
  width:fit-content;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  margin: 5px auto 5px 0px;
  
}

.searchSubmitButton{
  width:24px;
  height:24px;
  min-width: unset;
  padding:2px;
}
.searchSubmitIco{
  height:20px;
}
.searchContainer{
  min-width:450px;
  max-width:500px;
}



#searchTableHolder{
  max-height:800px
}

</style>

<script src="/js/searchCombine.js"></script>
<script>
  s = new SearchPanel()
  let query = $('searchQuery')
  let timeout = null
  query.addEventListener('keyup',function(){
    clearTimeout(timeout)
    timeout = setTimeout(function () {

      s.search()
    }, 1000);
  })
</script>
