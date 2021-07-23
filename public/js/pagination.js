
window.onload = function() {
    initEvent()
};

function initEvent() {
    let page_arr =  document.getElementsByClassName("page");
    let item_per_page_arr =  document.getElementsByClassName("item-per-page");
    for (let i = 0; i < page_arr.length; i++) {
        page_arr[i].addEventListener("click", handlePageClick,false);
    }
    for (let i = 0; i < item_per_page_arr.length; i++) {
        item_per_page_arr[i].addEventListener("click", handlePerPageClick,false);
    }
    document.getElementById('prev').addEventListener('click',handleSeekClick)
    document.getElementById('next').addEventListener('click',handleSeekClick)
}

function handleSeekClick() {
    let class_name = (this).className
    let item_per_page = document.querySelector('a.on.item-per-page').text
    if (class_name == 'prev'){
        searchData(1,item_per_page,'first_page_href')
    }
    if (class_name == 'next') {
        let page = document.getElementById('next').getAttribute('last_page')
        searchData(page,item_per_page,'last_page_href')
    }
}

function handlePageClick() {
    let page = (this).text
    let item_per_page = document.querySelector('a.on.item-per-page').text
    searchData(page,item_per_page);
}

function handlePerPageClick() {
    let page = document.querySelector('a.on.page').text
    let item_per_page = (this).text
    searchData(page,item_per_page);
}

function searchData(page,item_per_page,seek_method = '') {
    const xhttp = new XMLHttpRequest();

    xhttp.onload = function() {
        let pagination_div = document.getElementById("pagination");
        pagination_div.innerHTML = '';
        pagination_div.innerHTML = this.responseText;
        initEvent();
    }

    xhttp.open("GET", "/?page="+page+"&item_per_page="+item_per_page+"&seek_method="+seek_method);
    xhttp.send();
}
