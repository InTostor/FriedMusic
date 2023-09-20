
class SearchPanel{


  constructor(){
    self.searchQuery = $('searchQuery')
    self.searchQueryType = $('searchQueryType')
    self.tableHolder = $('searchTableHolder')
    if (isUrlParamSet(window.location.href,"sq") & isUrlParamSet(window.location.href,"sqt")){
    getUrlParams(window.location.href).forEach(element=>{
      if (element.key=="sq"){self.searchQuery.value=decodeURI(element.value)}
      if (element.key=="sqt"){self.searchQueryType.value=element.value}
    })
    this.search()
    }
  }

  search(){
    if (arguments.length==2){
      console.log(arguments[0])
      if (["Genre","Artist","Album"].includes(arguments[0])){
        console.log("search")
        self.searchQueryType.value = arguments[0]
        self.searchQuery.value = arguments[1]
        this.search()
        
      }
    }else{
      let query = self.searchQuery.value
      let type = self.searchQueryType.value
      updateCurrentURL("sq",query)
      updateCurrentURL("sqt",type)
      fetch(`/render/renderPlaylist.php?src=search&type=${type}&query=${query}`)
      .then((response) => response.text())
      .then(text => {self.tableHolder.innerHTML = text})
      fetch(`/api/rememberSearch.php?type=${type}&query=${query}`)
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
    return request.responseText
  }else{
    return false
  }
}