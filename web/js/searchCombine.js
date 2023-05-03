
class SearchPanel{


  constructor(){
    self.searchQuery = $('searchQuery')
    self.searchQueryType = $('searchQueryType')
    self.tableHolder = $('searchTableHolder')   
  }
  search(){
    let query = self.searchQuery.value
    let type = self.searchQueryType.value
    fetch(`/render/renderPlaylist.php?src=search&type=${type}&query=${query}`)
    .then((response) => response.text())
    .then(text => {self.tableHolder.innerHTML = text})
    fetch(`/api/rememberSearch.php?type=${type}&query=${query}`)
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
    return request.responseText
  }else{
    return false
  }
}