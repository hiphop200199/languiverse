const search = document.getElementById('search')

search.addEventListener('click',function(){
    let keyword = document.getElementById('keyword').value
    let categories = document.querySelectorAll('.category:checked')
    let cArr = []
    let editors = document.querySelectorAll('.who:checked')
    let eArr = []
    for(let i=0;i<categories.length;i++){
        cArr.push(categories[i].value)
    }
     for(let i=0;i<editors.length;i++){
        eArr.push(editors[i].value)
    }
    cArr.sort((a,b)=> a - b);
    eArr.sort((a,b)=> a - b);
    location.search = `?keyword=${keyword}&category=${cArr.join()}&editor=${eArr.join()}`;
})